<x-edit
    title="Edit Supply" 
    back-url="/items" 
    form-action="{{ route('itemsupply.update', $itemsupply->id) }}" 
    :fields="[
        [
            'label' => 'Item Name', 
            'name' => 'itemname', 
            'type' => 'text', 
            'id' => 'itemname', 
            'value' => old('itemname', $itemsupply->item->name),
        ],

        [
            'label' => 'Quantity', 
            'name' => 'quantity', 
            'type' => 'text', 
            'id' => 'quantity', 
            'value' => old('quantity', $itemsupply->quantity ?? ''),
        ],
        [
            'label' => 'Price', 
            'name' => 'price', 
            'type' => 'text', 
            'id' => 'price', 
            'value' => old('price', $itemsupply->buyprice ?? ''),
        ],
        [
            'label' => 'Supplier', 
            'name' => 'itemsupplier', 
            'type' => 'text', 
            'id' => 'itemsupplier', 
            'value' => old('itemsupplier', $itemsupply->supplier->name ?? ''),
        ],
    ]"
/>

