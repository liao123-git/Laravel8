<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\BaseModel
 *
 * @method static \Illuminate\Database\Eloquent\Builder|BaseModel newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|BaseModel newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|BaseModel query()
 * @mixin \Eloquent
 */
class BaseModel extends Model
{
    public function toArray()
    {
        // 返回值全部改成驼峰写法
        $items = parent::toArray(); // TODO: Change the autogenerated stub

        $keys = array_keys($items);
        $keys = array_map(function ($key) {
//            $key === "first_name" && dd(\Illuminate\Support\Str::studly("ASss_aa"));
            return lcfirst(\Illuminate\Support\Str::studly($key));
        }, $keys);

        $values = array_values($items);

        return array_combine($keys, $values);
    }

    /**
     * 乐观锁
     */
    public function cas()
    {
        throw_if(!$this->exists, \Exception::class, "model not exists when cas!");

        $dirty = $this->getDirty();
        if (empty($dirty)) return 0;

        $diff = array_diff(array_keys($dirty), array_keys($this->getOriginal()));
        throw_if(!empty($diff), \Exception::class, "key [" . implode(',', $diff) . "] not exists when cas!");

        $query = $this->newModelQuery()->where($this->getKeyName(), $this->getKey());

        foreach ($dirty as $key => $item) {
            $query = $query->where($key, $this->getOriginal($key));
        }

        $row = $query->update($dirty);
        if($row>0){
            // 更新模型对象
            $this->syncChanges();
            $this->syncOriginal();
        }

        return $row;
    }
}
