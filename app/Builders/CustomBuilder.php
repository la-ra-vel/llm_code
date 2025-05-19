<?php

namespace App\Builders;

use Illuminate\Database\Query\Builder as EloquentBuilder;

/**
 * Custom Builder for extending Eloquent Builder.
 */
class CustomBuilder extends EloquentBuilder
{
    // Add custom methods or overrides as needed
    // For example:

    /**
     * Example of a custom method
     */
    public function customMethod()
    {
        // Implement custom logic
        return $this->where('custom_field', 'value');
    }
}
