<?php

namespace Modules\Base\Params;



use Mcamara\LaravelLocalization\Facades\LaravelLocalization;
use Modules\Base\Service\GeneralBuildRelatedDataService;

abstract class GeneralParams
{

    protected ?int $organization_id = null;
    protected ?string $creatable_type = null;
    protected ?string $creatable_id = null;
    protected ?bool $is_active = true;
    protected ?bool $with_pagination = false;
    protected ?int $per_page = null;
    protected ?array $conditions = null;
    protected ?array $translatedAttributes = [];
    protected array $attributes = [];
    protected ?int $admin_id = null;

    public function __construct(array $attributes = [], ?int $organization_id = null, ?string $creatable_type = null, ?string $creatable_id = null, ?bool $is_active = true,
                                ?bool $with_pagination = false, ?int $per_page = null, ?array $conditions = null, ?array $translatedAttributes = [], ?int $admin_id = null)
    {
        $authValues = $this->resolveAuthValues();

        $this->attributes = $attributes;
        $this->organization_id = $organization_id ?? $authValues['organization_id'];
        $this->creatable_id = $creatable_id ?? $authValues['creatable_id'];
        $this->creatable_type = $creatable_type ?? $authValues['creatable_type'];
        $this->admin_id = $admin_id ?? $authValues['admin_id'];
        $this->is_active = $is_active;
        $this->with_pagination = $with_pagination;
        $this->per_page = $per_page;
        $this->conditions = $conditions;
        $this->translatedAttributes = $translatedAttributes;
    }

    protected function resolveAuthValues(): array
    {
        return [
            'admin_id' => auth("admin")?->id ?? null,
            'organization_id' => auth('employee')->user()?->organization_id ?? null,
            'creatable_id' => auth('employee')->user()->id ?? null,
            'creatable_type' => auth('employee')->user()?->getMorphClass() ?? null,
        ];
    }

    public function toMap(): array
    {
        $data = [
            'organization_id' => $this->organization_id,
            'creatable_type' => $this->creatable_type,
            'creatable_id' => $this->creatable_id,
            'is_active' => $this->is_active,
            'with_pagination' => $this->with_pagination,
            'per_page' => $this->per_page,
            'conditions' => $this->conditions,
            'translatedAttributes' => $this->translatedAttributes
        ];
        return $this->filterNull($data);
    }

    public function filterNull(array $data): array
    {
//        return array_filter($data);
        return array_filter($data, fn($value) => $value !== null);

    }

    public function BuildBody(array $data): self
    {
        return new static(...array_values($data));   // ... is (Splat Operator or Argument Unpacking)
    }

    public function getAttributes(): array
    {
        return $this->attributes;
    }

    public function handleTranslations(array $data): array
    {
        $model = $this->getModel();

        $translatedAttributes = [];
        if (property_exists($model, 'translatedAttributes')) {
            $translationFields = $model->translatedAttributes;
            $supportedLanguages = LaravelLocalization::getSupportedLocales();

            foreach ($supportedLanguages as $localeCode => $properties) {
                foreach ($translationFields as $field) {
                    if (isset($data[$field . '_' . $localeCode])) {
                        $translatedAttributes[$localeCode][$field] = $data[$field . '_' . $localeCode];
                    }
                }
            }
        }

        return $translatedAttributes;
    }

    abstract public function getModel();

    public function conditionsHandler(&$data, $operator, $fields, $value, $translation = true, $relation = null, $relationCondition = null)
    {

        if (!isset($data['conditions']) || !is_array($data['conditions'])) {
            $data['conditions'] = [];
        }

        if (!is_array($fields)) {
            $fields = [$fields];
        }

        foreach ($fields as $field) {
            $condition = [
                'field' => $field,
                'value' => $value,
                'operator' => $operator ?? '=',
                'translation' => $translation,
            ];

            if ($relation) {
                $condition['relation'] = $relation;

                if ($relationCondition) {
                    $condition['relation_condition'] = $relationCondition;
                }
            }

            $data['conditions'][] = $condition;
        }

        return $data['conditions'];
    }

    public function withPagination(): bool
    {
        return $this->with_pagination ?? false;
    }

    public function PerPage(): int
    {
        return $this->per_page ?? 10;
    }


//    public function handleAttachments(array $data): array
//    {
//        $attachments =isset($data['attachments']) ?
//            GeneralBuildRelatedDataService::buildRelatedAttachments(data: $data['attachments'], paramsClass: AttachmentParams::class, organization_id: $this->organization_id, title: class_basename($this->getModel()) . '/attachments') : [];
//        return $attachments;
//    }

//    public function buildAttachments(array $data, string $key, string $type,$is_base_64 = false): array
//    {
//        return isset($data[$key]) ?
//            GeneralBuildRelatedDataService::buildRelatedAttachments(
//                data: $data[$key],
//                paramsClass: AttachmentParams::class,
//                organization_id: $this->organization_id,
////                title: class_basename($this->getModel()) . "/$key",
//                title: class_basename($this->getModel()),
//                specialise_type: $type,
//                is_base_64: $is_base_64
//            ) : [];
//    }

    public function getAttachments(): array
    {
        return GeneralBuildRelatedDataService::processRelations($this->attachments ?? []) ?? [];
    }


}
