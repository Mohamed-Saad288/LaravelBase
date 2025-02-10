<?php

namespace Modules\Base\Traits;

use Illuminate\Database\Schema\Blueprint;

trait GeneralMigrationTrait
{

    public function addGeneralFields(Blueprint $table): void
    {
        $table->foreignId("organization_id")->nullable()->constrained("organizations")->onDelete("set null")->onUpdate("cascade");
        $table->boolean("is_active")->comment("1 = active , 0 = inactive");
        $table->nullableMorphs("creatable","creatable_indexes");
        $table->foreignId("admin_id")
            ->nullable()
            ->constrained("users")
            ->nullOnDelete();
        $table->softDeletes();
        $table->timestamps();
    }
}
