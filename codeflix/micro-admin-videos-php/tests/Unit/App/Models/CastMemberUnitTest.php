<?php

namespace Tests\Unit\App\Models;

use App\Models\CastMember;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use PHPUnit\Framework\TestCase;

class CastMemberUnitTest extends ModelTestCase
{
    protected function model(): Model
    {
        return new CastMember();
    }

    protected function traits(): array
    {
        return [HasFactory::class, SoftDeletes::class];
    }

    protected function fillables(): array
    {
        return ["id", "name", "type"];
    }

    protected function casts(): array
    {
        return [
            "id" => "string",
            "name" => "string",
            "type" => "int",
            "deleted_at" => "datetime",
            "updated_at" => "datetime"
        ];
    }
}
