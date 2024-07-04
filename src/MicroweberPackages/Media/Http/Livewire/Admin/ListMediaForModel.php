<?php

namespace MicroweberPackages\Media\Http\Livewire\Admin;

use Filament\Forms\Components\Component;
use Filament\Forms\Concerns\InteractsWithForms;
use Illuminate\Contracts\View\View;
use MicroweberPackages\Admin\Http\Livewire\AdminComponent;
use MicroweberPackages\Media\Models\Media;
use Livewire\Attributes\On;
use Filament\Forms\Get;
use Filament\Forms\Set;

class ListMediaForModel extends AdminComponent
{

    //use  InteractsWithForms;


    public $relType = '';
    public $relId = '';
    public $sessionId = '';
    public $createdBy = '';
    public $mediaItems = '';
    public $mediaIds = [];

    public $parentComponentName = '';


    public function getQueryBuilder()
    {
        $itemsQuery = Media::where('rel_type', $this->relType);

        if ($this->relId) {
            $itemsQuery->where('rel_id', $this->relId);
        } else if ($this->sessionId) {
            $itemsQuery->where('session_id', $this->sessionId);
            $itemsQuery->where('rel_id', 0);
        } else if ($this->createdBy) {
            $itemsQuery->where('created_by', $this->createdBy);
            $itemsQuery->where('rel_id', 0);


        }

        if ($this->relType) {
            $itemsQuery->where('rel_type', $this->relType);
        }
        $itemsQuery->orderBy('position', 'asc');
        return $itemsQuery;
    }

    #[On('mediaItemsSort')]
    public function mediaItemsSort($itemsSortedIds)
    {
        if (!$itemsSortedIds) {
            return;
        }
        $itemsQuery = $this->getQueryBuilder();

        //sort by position

        $position = 0;
        foreach ($itemsSortedIds as $itemsSortedId) {
            $position++;
            Media::where('id', $itemsSortedId)->update(['position' => $position]);
        }

        $this->refreshMediaData();

        $data = [
            'mediaIds' => $this->mediaIds
        ];
        if ($this->parentComponentName) {
            $this->dispatch('modifyComponentData', $data)->to($this->parentComponentName);
        }
        //  $this->dispatch('$refresh');

    }

    #[On('addMediaItem')]
    public function addMediaItem($url = false)
    {
        if (!$url) {
            return;
        }


        $itemsQuery = $this->getQueryBuilder();
        $itemsQuery = $itemsQuery->where('filename', $url);
        $mediaItem = $itemsQuery->first();

        //check if exists

        if (!$mediaItem) {
            $mediaItem = new Media();
            $mediaItem->rel_type = $this->relType;
            $mediaItem->rel_id = $this->relId;
            $mediaItem->filename = $url;
            if ($this->createdBy) {
                $mediaItem->created_by = $this->createdBy;
            }
            if (!$this->relId) {
                if ($this->sessionId) {
                    $mediaItem->session_id = $this->sessionId;
                }

            }

            $mediaItem->save();
        }


        $this->refreshMediaData();

        $data = [
            'mediaIds' => $this->mediaIds
        ];
        if ($this->parentComponentName) {
            $this->dispatch('modifyComponentData', $data)->to($this->parentComponentName);
        }
        //   $this->dispatch('$refresh');
    }

    public function refreshMediaData()
    {
        $itemsQuery = $this->getQueryBuilder();

        $this->mediaItems = $itemsQuery->get();
        if ($this->mediaItems) {
            $this->mediaIds = $this->mediaItems->pluck('id')->toArray();
        } else {
            $this->mediaIds = [];
        }

    }


    public function render(): View
    {

        $this->refreshMediaData();

        //   $this->parentComponent->data['mediaIds'] = $this->mediaIds;
        return view('media::admin.livewire.list-media-for-model');
    }
}
