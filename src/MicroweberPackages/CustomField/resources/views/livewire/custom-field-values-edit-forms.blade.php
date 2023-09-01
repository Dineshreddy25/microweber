<div>

    @if($customField->type =='text')
        <div class="mt-3">
            <x-microweber-ui::label for="as_text_area" value="Use as textarea" />
            <x-microweber-ui::toggle id="as_text_area" class="mt-1 block w-full" wire:model="state.options.as_text_area" />
        </div>
    @endif

    @if($customField->type == 'checkbox' || $customField->type == 'dropdown' || $customField->type == 'radio')

        <div class="mt-3">
            <x-microweber-ui::label value="Values" />

            <div id="js-sortable-items-holder-{{$this->id}}">
            @foreach($customField->fieldValue as $fieldValue)
                <div class="d-flex gap-3 mt-3 js-sortable-item" sort-key="{{ $fieldValue->id }}">
                    <div class="d-flex justify-content-center align-items-center">
                        <div class="js-sort-handle">
                            <svg class="mdi-cursor-move cursor-grab ui-sortable-handle"
                                 fill="#8e8e8e"
                                 xmlns="http://www.w3.org/2000/svg" height="24"
                                 viewBox="0 96 960 960" width="24">
                                <path
                                    d="M360 896q-33 0-56.5-23.5T280 816q0-33 23.5-56.5T360 736q33 0 56.5 23.5T440 816q0 33-23.5 56.5T360 896Zm240 0q-33 0-56.5-23.5T520 816q0-33 23.5-56.5T600 736q33 0 56.5 23.5T680 816q0 33-23.5 56.5T600 896ZM360 656q-33 0-56.5-23.5T280 576q0-33 23.5-56.5T360 496q33 0 56.5 23.5T440 576q0 33-23.5 56.5T360 656Zm240 0q-33 0-56.5-23.5T520 576q0-33 23.5-56.5T600 496q33 0 56.5 23.5T680 576q0 33-23.5 56.5T600 656ZM360 416q-33 0-56.5-23.5T280 336q0-33 23.5-56.5T360 256q33 0 56.5 23.5T440 336q0 33-23.5 56.5T360 416Zm240 0q-33 0-56.5-23.5T520 336q0-33 23.5-56.5T600 256q33 0 56.5 23.5T680 336q0 33-23.5 56.5T600 416Z"></path>
                            </svg>
                        </div>
                    </div>
                    <div class="w-full">
                        <x-microweber-ui::input class="mt-1 block w-full" wire:model="inputs.{{ $fieldValue->id }}" />
                        @error('inputs.'.$fieldValue->id) <span class="text-danger error">{{ $message }}</span>@enderror
                    </div>
                    <div class="d-flex gap-3 justify-content-center align-items-center">
                        <button class="btn btn-outline-success btn-sm" wire:click.prevent="add()">Add</button>
                        <button class="btn btn-outline-danger btn-sm" wire:click.prevent="remove({{$fieldValue->id}})">Delete</button>
                    </div>
                </div>
            @endforeach
            </div>
        </div>

    @elseif($customField->type == 'price')

    <div class="mt-1">
        <x-microweber-ui::label for="price" value="Price" />
        <x-microweber-ui::input-price id="price" wire:model.defer="state.value" />
    </div>

    @elseif($customField->type == 'property')
        <div class="mt-3">
            <x-microweber-ui::label for="value" value="Value" />
            <x-microweber-ui::textarea id="value" class="mt-1 block w-full" wire:model.defer="state.value" />
        </div>
    @elseif (isset($customField->options['as_text_area']) && $customField->options['as_text_area'])

        <div>
            <div class="mt-3">
                <x-microweber-ui::label for="value" value="Value" />
                <x-microweber-ui::textarea id="value" class="mt-1 block w-full" wire:model.defer="state.value" />
            </div>

            <div class="d-flex gap-3 mt-3">
                <div class="w-full">
                    <x-microweber-ui::label for="textarea_rows" value="Textarea Rows" />
                    <x-microweber-ui::input id="textarea_rows" class="mt-1 block w-full" wire:model="state.options.rows" />
                </div>
                <div class="w-full">
                    <x-microweber-ui::label for="textarea_cols" value="Textarea Cols" />
                    <x-microweber-ui::input id="textarea_cols" class="mt-1 block w-full" wire:model="state.options.cols" />
                </div>
            </div>
        </div>

    @else
        <div class="mt-3">
            <x-microweber-ui::label for="value" value="Value" />
            <x-microweber-ui::input id="value" class="mt-1 block w-full" wire:model.defer="state.value" />
        </div>
    @endif


    <div wire:ignore>
        <script>
            window.mw.custom_fields_values_sort = function () {
                if (!mw.$("#js-sortable-items-holder-{{$this->id}}").hasClass("ui-sortable")) {
                    mw.$("#js-sortable-items-holder-{{$this->id}}").sortable({
                        items: '.js-sortable-item',
                        axis: 'y',
                        handle: '.js-sort-handle',
                        update: function () {
                            setTimeout(function () {
                                var obj = {itemIds: []};
                                var sortableItems = document.querySelectorAll('#js-sortable-items-holder-{{$this->id}} .js-sortable-item');
                                sortableItems.forEach(function (item) {
                                    var id = item.getAttribute('sort-key');
                                    obj.itemIds.push(id);
                                });
                                window.Livewire.emit('onReorderCustomFieldValuesList', obj);
                            }, 300);
                        },
                        scroll: false
                    });
                }
            }
            window.mw.custom_fields_values_sort();
        </script>

    </div>
</div>
