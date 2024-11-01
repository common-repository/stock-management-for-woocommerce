
//current category selected
mp_stoman_categoryid = 0;

//page load
jQuery(document).ready(function () {

    if (mp_stoman_vars.wooCommerceActive) {

        mp_stoman_shared_displayoverlay();

        mp_stoman_pageadjust();

        //loading categories treeview
        mp_stoman_dashboard_loadcategoriestreeview()

    }

});

//adjusts the height of the main container
function mp_stoman_pageadjust() {

    //removes WP footer to allow more usable space
    jQuery("#wpfooter").remove();

    //removes padding at the bottom of wpcontent to allow more space for users
    jQuery("#wpbody-content").css("padding-bottom", "0px");

    //calculates the height of the wpwrap element
    var containerHeight = jQuery("#wpwrap").height() - jQuery("#mp-stoman-nvarbar").height() - jQuery("#mp-stoman-controltitle").height();
    //takes off a little of offset (admin bar at top)
    containerHeight = containerHeight - 101;
    //adjusts our container
    jQuery("#mp-stoman-contentpane").css("height", containerHeight + "px");

}

//loads the categories treeview, called on page load
function mp_stoman_dashboard_loadcategoriestreeview() {

    jQuery.ajax({

        url: mp_stoman_vars.ajaxHandlerUrl,
        type: 'post',
        dataType: 'json',
        data:
        {
            action: 'mp_stoman_categories_listfortreeview',
            security: mp_stoman_vars.ajaxNonce
        },
        success: function (response) {

            jQuery("#mp-stoman-tree").fancytree({
                checkbox: false,
                source: response.data,
                activate: function (event, data) {
                    mp_stoman_dashboard_categoryclicked(data.node.key);
                }
            });

            //selects root node
            var treeView = jQuery("#mp-stoman-tree").fancytree("getTree");
            var rootNode = treeView.getNodeByKey("-1");
            rootNode.setExpanded();
            rootNode.setActive();

        }

    });

}

//click on a category in the treeview
function mp_stoman_dashboard_categoryclicked(categoryId) {

    //set the global var
    mp_stoman_categoryid = categoryId;

    //loads the list of products
    mp_stoman_dashboard_loadproducts();

}

//loads the list of products
function mp_stoman_dashboard_loadproducts() {

    mp_stoman_shared_displayoverlay();

    jQuery.ajax({

        url: mp_stoman_vars.ajaxHandlerUrl,
        type: 'post',
        dataType: 'json',
        data:
        {
            action: 'mp_stoman_products_list',
            security: mp_stoman_vars.ajaxNonce,
            CategoryId: mp_stoman_categoryid,
            SearchKey: jQuery("#mp-stoman-dashboard-txt-searchproduct").val()
        },
        success: function (response) {

            jQuery('#prodTable').DataTable(
                {
                    "dom": 'tp',
                    "bDestroy": true,
                    "bPaginate": false,
                    "bAutoWidth": false,
                    data: response.data,
                    columns:
                    [
                        { "width": "5%", data: "productId", className: "text-center spanMarginTop" },
                        { "width": "20%", data: "productSKU", className: "spanMarginTop" },
                        { "width": "30%", data: "productTitle", className: "spanMarginTop" },
                        { "width": "20%", data: "productStockEnabled", className: "text-center switcherySmallAdjust", render: function (data, type, full, meta) { return mp_stoman_dashboard_loadproducts_returncheckboxstockenabled(data, full.productId); } },
                        { "width": "20%", data: "productStockQuantity", className: "text-center", render: function (data, type, full, meta) { return mp_stoman_dashboard_loadproducts_returntxtstockquantity(data, full.productId, full.productStockEnabled); } },
                        { "width": "10%", data: "productId", className: "text-right", render: function (data, type, full, meta) { return '<button title=\'Save changes for this Product\' onclick=\'mp_stoman_dashboard_saverowbuttonclicked(' + data + ');\' class=\'btn btn-primary btn-primary-small\'><i class=\'fa fa-save\'></i></button>'; } }
                    ],
                    "order": [[0, "desc"]]
                });

            //transforms checkboxes with switchery
            //var elem = document.querySelector('.mp-stoman-switchery');
            //var init = new Switchery(elem);

            var checkboxes = document.querySelectorAll('.mp-stoman-switchery');
            [].forEach.call(checkboxes, function (checkbox) {

                var createdSwitchery = new Switchery(checkbox, { size: "small" });

                //attaching cehcked changed event
                //createdSwitchery.onchange = function () {
                //    //changeField.innerHTML = changeCheckbox.checked;
                //    //console.log(createdSwitchery.checked);
                //    alert("ciao");
                //};

            });

            mp_stoman_shared_removeoverlay();

        }

    });

}


//renders the checkbox for enable stock
function mp_stoman_dashboard_loadproducts_returncheckboxstockenabled(isEnabled, productId) {

    var checked = "";
    if (isEnabled == true) {
        checked = " checked ";
    }

    return '<input type=\'checkbox\' class=\'mp-stoman-switchery\' ' + checked + ' \ id=\'mp-stoman-chk-managestock-' + productId + '\' onchange=\mp_stoman_dashboard_loadproducts_chkenablestockchanged(\'' + productId + '\'); \' >';
}


//fired at checkbox status change
function mp_stoman_dashboard_loadproducts_chkenablestockchanged(productId) {

    //reading checkbox status
    var checkboxStatus = jQuery("#mp-stoman-chk-managestock-" + productId).prop("checked");

    //disables or enables the textbox
    var textbox = jQuery("#mp-stoman-txt-stockquantity-" + productId);
    if (checkboxStatus == true) {
        textbox.removeClass("mp-stoman-tablestock-qtytxtinput-disabled");
        textbox.removeAttr("disabled");
    }
    else {
        textbox.addClass("mp-stoman-tablestock-qtytxtinput-disabled");
        textbox.attr("disabled", "true");
    }

}


//renders the textbox for stock quantites
function mp_stoman_dashboard_loadproducts_returntxtstockquantity(stockQuantity, productId, productStockEnabled) {

    var cssclass = "mp-stoman-panels-input mp-stoman-tablestock-qtytxtinput";
    if (productStockEnabled != true) {
        cssclass = cssclass + " mp-stoman-tablestock-qtytxtinput-disabled";
    }

    return '<input class=\'' + cssclass + '\' type=\'number\' min=\'-999999\' max=\'999999\' id=\'mp-stoman-txt-stockquantity-' + productId + '\' value=\'' + stockQuantity + '\' onkeypress=\'return mp_stoman_isNumeric(event)\' oninput=\'mp_stoman_maxLengthCheck(this)\' />';
}



//saves the stock details of a product
function mp_stoman_dashboard_saverowbuttonclicked(productid) {

    mp_stoman_shared_displayoverlay();

    jQuery.ajax(

        {

            url: mp_stoman_vars.ajaxHandlerUrl,
            type: 'post',
            dataType: 'json',
            data:
            {
                action: 'mp_stoman_products_stockdetails_save',
                security: mp_stoman_vars.ajaxNonce,
                ProductId: productid,
                NewStockQuantity: jQuery("#mp-stoman-txt-stockquantity-" + productid).val(),
                StockEnabled: jQuery("#mp-stoman-chk-managestock-" + productid).prop("checked") == true
            },
            success: function (response) {

                mp_stoman_shared_removeoverlay();

                mp_stoman_shared_displaynotify("Product details for '<strong>" + response.data + "</strong>' saved correctly.");

            }

        }

    );

}