<?php

namespace MicroweberPackages\LiveEdit\Http\Livewire\ItemsEditor;


use Filament\Tables\Actions\CreateAction;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use MicroweberPackages\Filament\Tables\Columns\ImageUrlColumn;
use MicroweberPackages\LiveEdit\Filament\Admin\Pages\Abstract\LiveEditModuleSettings;
use MicroweberPackages\LiveEdit\Models\ModuleItemSushi;
use MicroweberPackages\Modules\Tabs\Models\Tab;

class ModuleSettingsItemsEditorComponent extends LiveEditModuleSettings implements HasTable
{

    use InteractsWithTable;

    public string $module = '';
    public array $editorSettings = [];

    protected static string $view = 'microweber-live-edit::module-items-editor';


    public function getEditorSettings(): array
    {
        return [];
    }

    public function getModel(): string
    {
        return ModuleItemSushi::class;
    }

    public function table(Table $table): Table
    {

        $builtTable = $table->query($this->getModel()::queryForOptionGroup($this->getOptionGroup()));

        $editorSettings = $this->getEditorSettings();

        $formFields = [];
        $tableColumns = [];
        if (isset($editorSettings['schema'])) {

            $formFieldsFromSchema = $this->schemaToFormFields($editorSettings['schema']);
            if ($formFieldsFromSchema) {
                $formFields = array_merge($formFields, $formFieldsFromSchema);
            }



//            foreach ($editorSettings['schema'] as $schema) {
//
//
//
////                if ($schema['type'] == 'text') {
////                    $formFields[] = TextInput::make($schema['name'])
////                        ->label($schema['label'])
////                        ->placeholder($schema['placeholder'])
////                        ->maxLength(255);
////
////                }if ($schema['type'] == 'textarea') {
////                    $formFields[] = Textarea::make($schema['name'])
////                        ->label($schema['label'])
////                        ->placeholder($schema['placeholder'])
////                        ->maxLength(255);
////                }
////                if ($schema['type'] == 'image') {
////                    $formFields[] = MwFileUpload::make($schema['name'])
////                        ->label($schema['label'])
////                        ->placeholder($schema['placeholder']);
////                }
////                if ($schema['type'] == 'color') {
////                    $formFields[] = ColorPicker::make($schema['name'])
////                        ->label($schema['label'])
////                        ->placeholder($schema['placeholder']);
////                }
////                if ($schema['type'] == 'select') {
////                    $formFields[] = Select::make($schema['name'])
////                        ->label($schema['label'])
////                         ->options($schema['options'])
////                        ->placeholder($schema['placeholder']);
////                }
////                if ($schema['type'] == 'toggle') {
////                    $formFields[] = Toggle::make($schema['name'])
////                        ->label($schema['label']);
////                }
//
//
//            }
        }

        if (isset($editorSettings['config']['listColumns'])) {
            foreach ($editorSettings['config']['listColumns'] as $key => $columnSettings) {
                if (isset($columnSettings['type'])) {

                    // $name must  start with options.
                    if (strpos($key, 'options.') !== 0) {
                        $key = 'options.' . $key;
                    }


                    if ($columnSettings['type'] == 'text') {
                        $tableColumns[] = TextColumn::make($key)
                            ->label($columnSettings['label'])
                            ->searchable();
                    }
                    if ($columnSettings['type'] == 'image') {
                        $tableColumns[] = ImageUrlColumn::make($key)
                            ->label($columnSettings['label'])
                            ->imageUrl(function ($record) use ($key) {
                                return $record[$key];
                            })
                            ->searchable();
                    }
                }
            }
        }
        $builtTable->columns($tableColumns);

        $headerActions = [];
        if (isset($editorSettings['config']['addButtonText'])) {
            $headerActions[] = CreateAction::make()
                ->label($editorSettings['config']['addButtonText'])
                ->modalHeading($editorSettings['config']['addButtonText'])
                ->slideOver()
                ->form($formFields)
                ->createAnother(false)
                ->after(function () {
                    $this->dispatch('mw-option-saved',
                        optionGroup: $this->optionGroup
                    );
                });
        }
        $actions = [];
        if (isset($editorSettings['config']['editButtonText'])) {
            $actions[] = EditAction::make($editorSettings['config']['editButtonText'])
                ->slideOver()
                ->hiddenLabel(true)
                ->modalHeading($editorSettings['config']['editButtonText'])
                ->form($formFields)->after(function () {
                    $this->dispatch('mw-option-saved',
                        optionGroup: $this->optionGroup
                    );
                });
        }
        if (isset($editorSettings['config']['deleteButtonText'])) {
            $actions[] = DeleteAction::make($editorSettings['config']['deleteButtonText'])
                ->slideOver()
                ->modalHeading($editorSettings['config']['deleteButtonText'])
                ->hiddenLabel(true)
                ->after(function () {
                    $this->dispatch('mw-option-saved',
                        optionGroup: $this->optionGroup
                    );
                });
        }

        //            $builtTable->contentGrid([
//                'md' => 1,
//                'lg' => 1,
//                'xl' => 1,
//            ])

        $builtTable->reorderRecordsTriggerAction(function () {
            $tableRecords = $this->getTableRecords();
            if ($tableRecords) {
                foreach ($tableRecords->toArray() as $tableRecord) {
                    if (isset($tableRecord['id'])) {
                        $findTab = $this->getModel()::where('id', $tableRecord['id'])->first();
                        if ($findTab) {
                            $findTab->position = $tableRecord['position'];
                            $findTab->save();
                        }
                    }
                }
            }
        })
            ->defaultSort('position')
            ->reorderable('position')
            ->headerActions($headerActions)
            ->actions($actions);


        return $builtTable;
    }

}
