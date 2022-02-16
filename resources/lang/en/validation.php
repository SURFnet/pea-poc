<?php

declare(strict_types=1);

return [
    /*
    |--------------------------------------------------------------------------
    | Validation Language Lines
    |--------------------------------------------------------------------------
    |
    | The following language lines contain the default error messages used by
    | the validator class. Some of these rules have multiple versions such
    | as the size rules. Feel free to tweak each of these messages here.
    |
    */

    'accepted'        => 'This field must be accepted.',
    'active_url'      => 'This field is not a valid URL.',
    'after'           => 'This field must be a date after :date.',
    'after_or_equal'  => 'This field must be a date after or equal to :date.',
    'alpha'           => 'This field must only contain letters.',
    'alpha_dash'      => 'This field must only contain letters, numbers, dashes and underscores.',
    'alpha_num'       => 'This field must only contain letters and numbers.',
    'array'           => 'This field must be an array.',
    'before'          => 'This field must be a date before :date.',
    'before_or_equal' => 'This field must be a date before or equal to :date.',
    'between'         => [
        'numeric' => 'This field must be between :min and :max.',
        'file'    => 'This field must be between :min and :max kilobytes.',
        'string'  => 'This field must be between :min and :max characters.',
        'array'   => 'This field must have between :min and :max items.',
    ],
    'boolean'        => 'This field must be true or false.',
    'confirmed'      => 'This field\'s confirmation does not match.',
    'date'           => 'This field is not a valid date.',
    'date_equals'    => 'This field must be a date equal to :date.',
    'date_format'    => 'This field does not match the format :format.',
    'different'      => 'This field and :other must be different.',
    'digits'         => 'This field must be :digits digits.',
    'digits_between' => 'This field must be between :min and :max digits.',
    'dimensions'     => 'This field has invalid image dimensions.',
    'distinct'       => 'This field has a duplicate value.',
    'email'          => 'This field must be a valid email address.',
    'ends_with'      => 'This field must end with one of the following: :values.',
    'exists'         => 'The selected value is invalid.',
    'file'           => 'This field must be a file.',
    'filled'         => 'This field must have a value.',
    'gt'             => [
        'numeric' => 'This field must be greater than :value.',
        'file'    => 'This field must be greater than :value kilobytes.',
        'string'  => 'This field must be greater than :value characters.',
        'array'   => 'This field must have more than :value items.',
    ],
    'gte' => [
        'numeric' => 'This field must be greater than or equal to :value.',
        'file'    => 'This field must be greater than or equal to :value kilobytes.',
        'string'  => 'This field must be greater than or equal to :value characters.',
        'array'   => 'This field must have :value items or more.',
    ],
    'image'    => 'This field must be an image.',
    'in'       => 'The selected value is invalid.',
    'in_array' => 'This field does not exist in :other.',
    'integer'  => 'This field must be an integer.',
    'ip'       => 'This field must be a valid IP address.',
    'ipv4'     => 'This field must be a valid IPv4 address.',
    'ipv6'     => 'This field must be a valid IPv6 address.',
    'json'     => 'This field must be a valid JSON string.',
    'lt'       => [
        'numeric' => 'This field must be less than :value.',
        'file'    => 'This field must be less than :value kilobytes.',
        'string'  => 'This field must be less than :value characters.',
        'array'   => 'This field must have less than :value items.',
    ],
    'lte' => [
        'numeric' => 'This field must be less than or equal to :value.',
        'file'    => 'This field must be less than or equal to :value kilobytes.',
        'string'  => 'This field must be less than or equal to :value characters.',
        'array'   => 'This field must not have more than :value items.',
    ],
    'max' => [
        'numeric' => 'This field must not be greater than :max.',
        'file'    => 'This field must not be greater than :max kilobytes.',
        'string'  => 'This field must not be greater than :max characters.',
        'array'   => 'This field must not have more than :max items.',
    ],
    'mimes'     => 'This field must be a file of type: :values.',
    'mimetypes' => 'This field must be a file of type: :values.',
    'min'       => [
        'numeric' => 'This field must be at least :min.',
        'file'    => 'This field must be at least :min kilobytes.',
        'string'  => 'This field must be at least :min characters.',
        'array'   => 'This field must have at least :min items.',
    ],
    'multiple_of'          => 'This field must be a multiple of :value.',
    'not_in'               => 'The selected value is invalid.',
    'not_regex'            => 'This field\'s format is invalid.',
    'numeric'              => 'This field must be a number.',
    'password'             => 'The password is incorrect.',
    'present'              => 'This field must be present.',
    'regex'                => 'This field\'s format is invalid.',
    'required'             => 'This field is required.',
    'required_if'          => 'This field is required when :other is :value.',
    'required_unless'      => 'This field is required unless :other is in :values.',
    'required_with'        => 'This field is required when :values is present.',
    'required_with_all'    => 'This field is required when :values are present.',
    'required_without'     => 'This field is required when :values is not present.',
    'required_without_all' => 'This field is required when none of :values are present.',
    'prohibited'           => 'This field is prohibited.',
    'prohibited_if'        => 'This field is prohibited when :other is :value.',
    'prohibited_unless'    => 'This field is prohibited unless :other is in :values.',
    'same'                 => 'This field and :other must match.',
    'size'                 => [
        'numeric' => 'This field must be :size.',
        'file'    => 'This field must be :size kilobytes.',
        'string'  => 'This field must be :size characters.',
        'array'   => 'This field must contain :size items.',
    ],
    'starts_with' => 'This field must start with one of the following: :values.',
    'string'      => 'This field must be a string.',
    'timezone'    => 'This field must be a valid timezone.',
    'unique'      => 'This field has already been taken.',
    'uploaded'    => 'This field failed to upload.',
    'url'         => 'This field\'s format is invalid.',
    'uuid'        => 'This field must be a valid UUID.',

    /*
    |--------------------------------------------------------------------------
    | Custom Validation Language Lines
    |--------------------------------------------------------------------------
    |
    | Here you may specify custom validation messages for attributes using the
    | convention "attribute.rule" to name the lines. This makes it quick to
    | specify a specific custom language line for a given attribute rule.
    |
    */

    'custom' => [
        'rating' => [
            'in' => 'At least a one star rating must be given',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Custom Validation Attributes
    |--------------------------------------------------------------------------
    |
    | The following language lines are used to swap our attribute placeholder
    | with something more reader friendly such as "E-Mail Address" instead
    | of "email". This simply helps us make our message more expressive.
    |
    */

    'attributes' => [],

    // Vendor validation rules
    'phone' => 'The entered phone number is invalid. Please try again with a country code.',

    // Custom validation rules
    'db_string'                  => 'This field is longer than :length characters or no valid text',
    'postal_code'                => 'This field must have the following format: 1234 AB',
    'vat_number'                 => 'This field does not contain a valid VAT number.',
    'chamber_of_commerce_number' => 'This field does not contain a valid Chamber of Commerce number.',
];
