<?php

declare(strict_types=1);

namespace App\Providers;

use Collective\Html\FormFacade as Form;
use Illuminate\Support\ServiceProvider;

class FormServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        $this->initializeAdminComponents();
    }

    private function initializeAdminComponents(): void
    {
        $defaultComponents = [
            'text',
            'textarea',
            'wysiwyg',
            'email',
            'phone',
            'number',
            'date',
        ];

        foreach ($defaultComponents as $component) {
            Form::component('bs' . ucfirst($component), 'components.form.admin.' . $component, [
                'name'       => null,
                'value'      => null,
                'label'      => null,
                'attributes' => [],
                'meta'       => $this->getMetaOptions(),
            ]);
        }

        Form::component('bsPassword', 'components.form.admin.password', [
            'name'       => null,
            'label'      => null,
            'attributes' => [],
            'meta'       => $this->getMetaOptions(),
        ]);

        Form::component('bsCheckbox', 'components.form.admin.checkbox', [
            'name'       => null,
            'value'      => null,
            'label'      => null,
            'attributes' => [],
            'meta'       => $this->getMetaOptions(),
        ]);

        Form::component('bsRadio', 'components.form.admin.radio', [
            'name'       => null,
            'value'      => null,
            'label'      => null,
            'options'    => [],
            'attributes' => [],
            'meta'       => $this->getMetaOptions(),
        ]);

        $selectFields = [
            ''         => '',
            'Multiple' => '-multiple',
        ];

        foreach ($selectFields as $name => $file) {
            Form::component('bsSelect' . $name, 'components.form.admin.select' . $file, [
                'name'       => null,
                'value'      => null,
                'label'      => null,
                'options'    => [],
                'attributes' => [],
                'meta'       => $this->getMetaOptions(),
            ]);
        }

        Form::component('bsFile', 'components.form.admin.file', [
            'name'       => null,
            'model'      => null,
            'label'      => null,
            'route'      => null,
            'type'       => null,
            'attributes' => [],
            'meta'       => $this->getMetaOptions(),
        ]);

        Form::component('bsPrice', 'components.form.admin.price', [
            'name'       => null,
            'value'      => null,
            'label'      => null,
            'currency'   => '€',
            'attributes' => [],
            'meta'       => $this->getMetaOptions(),
        ]);

        Form::component('bsStatic', 'components.form.admin.static', [
            'name'       => null,
            'value'      => null,
            'model'      => null,
            'label'      => null,
            'meta'       => $this->getMetaOptions(),
            'text_empty' => null,
        ]);
    }

    private function getMetaOptions(): array
    {
        return [
            'required'     => false,
            'text'         => null,
            'multilingual' => false,
        ];
    }
}
