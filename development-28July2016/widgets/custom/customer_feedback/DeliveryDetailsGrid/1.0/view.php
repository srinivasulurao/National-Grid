<div id="rn_<?= $this->instanceID ?>" class="<?= $this->classList ?>" style='float:right'>
<button id='delivery_detail_obj' type="button">Delivery Details</button>
<span id="delivery_details_obj_grid">
<span class='delivery_details_obj_play'></span>
<br><button type='button' id='refresh_delivery_obj'>Refresh</button> <button type='button' id='close_delivery_obj'>Close</button>
</span>
</div>

<style>
#delivery_detail_obj{
    bottom: 48px;
    position: relative;
    float: right;
    height: 37px;
    right: 10px;
}

#delivery_details_obj_grid{
    border: 4px solid black;
    background: white;
    padding: 15px;
    width: 350px;
    position: absolute;
    z-index: 501;
    border-radius: 4px;
    display:none;
}
</style>