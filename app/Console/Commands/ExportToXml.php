<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Models\ContentPage;
use App\Models\CustomField;
use App\Models\CustomFieldValue;
use App\Models\Experience;
use App\Models\Institute;
use App\Models\InstituteTool;
use App\Models\Tag;
use App\Models\Tool;
use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use XMLWriter;

class ExportToXml extends Command
{
    /** @var string */
    protected $signature = 'app:export-to-xml';

    /** @var string */
    protected $description = 'Exports the data to XML.';

    private const DISK = 'local';
    private const FILENAME = 'export.xml';

    private XMLWriter $xmlWriter;

    public function __construct()
    {
        parent::__construct();
    }

    public function handle(): int
    {
        $this->info('Started export.');

        $isWrittenSuccessfully = Storage::disk(self::DISK)->put(self::FILENAME, $this->getXmlAsString());
        if ($isWrittenSuccessfully) {
            $this->info('Export finished successfully.');
            $this->info('Export file contents:');
            $this->info(Storage::disk(self::DISK)->get(self::FILENAME));

            $this->info(sprintf('Export path: %s', Storage::disk(self::DISK)->path(self::FILENAME)));
        } else {
            $this->error('Export failed: Something went wrong while writing to the file.');
        }

        return 0;
    }

    private function getXmlAsString(): string
    {
        $this->xmlWriter = new XmlWriter();
        $this->xmlWriter->openMemory();

        $this->xmlWriter->setIndent(true);
        $this->xmlWriter->setIndentString('  ');

        $this->xmlWriter->startDocument('1.0', 'UTF-8');
        $this->xmlWriter->startElement('sreapp');

        $this->addUsers();
        $this->addInstitutes();
        $this->addTools();
        $this->addTags();
        $this->addExperiences();
        $this->addCustomFields();
        $this->addCustomFieldValues();
        $this->addContentPages();

        $this->xmlWriter->endElement();

        return $this->xmlWriter->outputMemory();
    }

    private function addUsers(): void
    {
        $this->xmlWriter->startElement('users');

        /** @var User $user */
        foreach (User::with(['followingTools'])->get() as $user) {
            $this->xmlWriter->startElement('user');
            $this->addModelAsChild($user);

            $this->xmlWriter->startElement('following_tool_ids');
            $this->writeValueToXml($user->followingTools->pluck('id')->implode(','));
            $this->xmlWriter->endElement();

            $this->xmlWriter->endElement();
        }

        $this->xmlWriter->endElement();
    }

    private function addInstitutes(): void
    {
        $this->xmlWriter->startElement('institutes');

        /** @var Institute $institute */
        foreach (Institute::with(['tools'])->get() as $institute) {
            $this->xmlWriter->startElement('institute');

            $this->addModelAsChild($institute);

            $this->xmlWriter->startElement('tools');
            foreach ($institute->tools as $tool) {
                $this->xmlWriter->startElement('tool');
                $this->addModelAsChild($tool);
                $this->xmlWriter->endElement();
            }
            $this->xmlWriter->endElement();

            $this->xmlWriter->startElement('institute_tools');
            foreach (InstituteTool::forInstitute($institute)->get() as $instituteTool) {
                $this->xmlWriter->startElement('institute_tool');
                $this->addModelAsChild($instituteTool);

                $this->xmlWriter->startElement('alternative_tool_ids');
                $this->writeValueToXml($instituteTool->alternativeTools->pluck('id')->implode(','));
                $this->xmlWriter->endElement();

                $this->xmlWriter->endElement();
            }
            $this->xmlWriter->endElement();

            $this->xmlWriter->endElement();
        }

        $this->xmlWriter->endElement();
    }

    private function addTools(): void
    {
        $this->xmlWriter->startElement('tools');

        /** @var Tool $tool */
        foreach (Tool::all() as $tool) {
            $this->xmlWriter->startElement('tool');
            $this->addModelAsChild($tool);
            $this->xmlWriter->endElement();
        }

        $this->xmlWriter->endElement();
    }

    private function addTags(): void
    {
        $this->xmlWriter->startElement('tags');

        /** @var Tag $tag */
        foreach (Tag::all() as $tag) {
            $this->xmlWriter->startElement('tag');
            $this->addModelAsChild($tag);
            $this->xmlWriter->endElement();
        }

        $this->xmlWriter->endElement();
    }

    private function addExperiences(): void
    {
        $this->xmlWriter->startElement('experiences');

        /** @var Experience $experience */
        foreach (Experience::all() as $experience) {
            $this->xmlWriter->startElement('experience');
            $this->addModelAsChild($experience);
            $this->xmlWriter->endElement();
        }

        $this->xmlWriter->endElement();
    }

    private function addCustomFields(): void
    {
        $this->xmlWriter->startElement('custom_fields');

        /** @var CustomField $customField */
        foreach (CustomField::all() as $customField) {
            $this->xmlWriter->startElement('experience');
            $this->addModelAsChild($customField);
            $this->xmlWriter->endElement();
        }

        $this->xmlWriter->endElement();
    }

    private function addCustomFieldValues(): void
    {
        $this->xmlWriter->startElement('custom_field_values');

        /** @var CustomFieldValue $customFieldValue */
        foreach (CustomFieldValue::all() as $customFieldValue) {
            $this->xmlWriter->startElement('custom_field_value');
            $this->addModelAsChild($customFieldValue);
            $this->xmlWriter->endElement();
        }

        $this->xmlWriter->endElement();
    }

    private function addContentPages(): void
    {
        $this->xmlWriter->startElement('content_pages');

        /** @var ContentPage $contentPage */
        foreach (ContentPage::all() as $contentPage) {
            $this->xmlWriter->startElement('content_page');
            $this->addModelAsChild($contentPage);
            $this->xmlWriter->endElement();
        }

        $this->xmlWriter->endElement();
    }

    private function addModelAsChild(Model $model): void
    {
        foreach (array_keys($model->getAttributes()) as $attribute) {
            $this->xmlWriter->startElement($attribute);

            $rawValue = $model->getAttributeValue($attribute);
            $formattedValue = $this->getValueForXml($rawValue);
            $this->writeValueToXml($formattedValue);

            $this->xmlWriter->endElement();
        }
    }

    private function getValueForXml(mixed $rawValue): string
    {
        if (is_array($rawValue)) {
            $rawValue = implode(', ', $rawValue);
        }

        return (string) $rawValue;
    }

    private function writeValueToXml(string $valueForXml): void
    {
        if ($valueForXml === '') {
            return;
        }

        if (!is_numeric($valueForXml)) {
            $this->xmlWriter->writeCdata($valueForXml);
        } else {
            $this->xmlWriter->text($valueForXml);
        }
    }
}
