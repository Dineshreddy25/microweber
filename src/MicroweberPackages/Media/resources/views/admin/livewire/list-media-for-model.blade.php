<div>

    @php
        $suffix = '';

        $suffix = $this->getId();

    @endphp

    <div>

        <script>
            document.addEventListener('livewire:init', function () {

            });
        </script>
        <script>
            function mwMediaManagerComponent({mediaIds}) {
                return {
                    mediaIds,
                    modalImageSettingsOpen: false,
                    selectedImages: [],

                    init() {

                        //     console.log('mwMediaManagerComponent', this.mediaIds);
                    },


                    editMediaOptionsById(id) {


                        this.$wire.mountAction('editAction', {id: id})

                    },

                    async bulkDeleteSelectedMedia() {
                        const dialogConfirm = await mw.confirm('Are you sure you want to delete selected images?').promise()
                        if (dialogConfirm) {
                            this.$wire.dispatch('deleteMediaItemsByIds', {ids: this.selectedImages})
                        }
                    },

                    async deleteMediaById(id) {

                        const dialogConfirm = await mw.confirm('Are you sure you want to delete this image?').promise()
                        if (dialogConfirm) {
                            this.$wire.dispatch('deleteMediaItemById', {id: id})
                        }
                    }
                }
            }
        </script>

        <div
            class="w-full flex flex-col p-3 items-center justify-center border border-gray-300 rounded-md text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-offset-gray-100 focus:ring-indigo-500">
            <button

                class="w-full py-6 full flex flex-col items-center justify-center"
                type="button" x-on:click="()=> {

                mw.filePickerDialog((url) => {
                   $dispatch('addMediaItem', { url: url })
                });

                }">

                <span>
                    Select media file or <b class="text-yellow-500 font-bold">Upload</b>
                </span>
            </button>

            <hr class="h-px mb-8 mt-4 bg-gray-200 border-0 dark:bg-gray-700 w-full">


            <div class="w-full mb-3">
                @if($this->mediaItems and !empty($this->mediaItems))

                    <div

                        x-data="mwMediaManagerComponent({
                            mediaIds: $wire.$entangle('mediaIds')
                        })"
                        x-on:end="



                    itemsSortedIds = $event.target.querySelectorAll('[x-sortable-item]');

                    itemsSortedIdsArray = [];
                    for (var i = 0; i < itemsSortedIds.length; i++) {
                        itemsSortedIdsArray.push(itemsSortedIds[i].getAttribute('x-sortable-item'));
                    }
                    $dispatch('mediaItemsSort', { itemsSortedIds: itemsSortedIdsArray })
                    "
                        class="admin-thumbs-holder-wrapper"
                    >


                        <div x-show="mediaIds && mediaIds.length > 0 && selectedImages && selectedImages.length > 0"
                             class="admin-thumbs-holder-bulk-actions">


                            <button type="button" @click="bulkDeleteSelectedMedia()">Delete selected</button>


                        </div>


                        <div class="admin-thumbs-holder" x-sortable>
                            @foreach($this->mediaItems as $item)

                                <div
                                    x-sortable-handle
                                    x-sortable-item="{{ $item->id }}"
                                    x-data-id="{{ $item->id }}"
                                    class="background-image-holder admin-thumb-item ui-sortable-handle"

                                >




                            <span class="mw-post-media-img" style="background-image: url('{{ $item->filename }}');">


                            </span>



                                    <a @click="editMediaOptionsById('{{ $item->id }}')" class="image-settings settings-img tip">
                                        @svg('mw-media-item-edit-small')
                                    </a>

                                    <a @click="deleteMediaById('{{ $item->id }}')">
                                        @svg('mw-media-item-delete-small')
                                    </a>




                                    <label class="form-check form-check-inline">
                                        <input type="checkbox" x-model="selectedImages" value="{{ $item->id }}"
                                               class="form-check-input">
                                    </label>
                                </div>

                            @endforeach




                        </div>


                    </div>
                @endif

            </div>
        </div>


        <x-filament-actions::modals/>
    </div>
</div>



