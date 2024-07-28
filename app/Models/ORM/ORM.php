<?php

namespace App\Models\ORM;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class ORM extends Model
{
    public function deleteMr(): bool
    {
        if (method_exists($this, 'beforeDelete')) {
            $this->beforeDelete();
        }

        $this->delete();

        if (!method_exists($this, 'afterDelete')) {
            return true;
        }
        $this->afterDelete();

        return true;
    }

    public function id(): ?int
    {
        return $this->attributes['id'] ?? null;
    }

    public function saveMr(): ?int
    {
        if (method_exists($this, 'beforeSave')) {
            $this->beforeSave();
        }

        $this->save();

        if (method_exists($this, 'afterSave')) {
            $this->afterSave();
        }

        if (method_exists($this, 'flushAffectedCaches')) {
            $this->flushAffectedCaches();
        }

        return $this->id();
    }

    public static function getTableName(): string
    {
        return (new static())->getTable();
    }

    public static function loadBy(?int $value): ?static
    {
        return static::find($value);
    }

    /**
     * @throws ModelNotFoundException
     */
    public static function loadByOrDie(?int $value): static
    {
        return self::findOrFail($value);
    }
}
