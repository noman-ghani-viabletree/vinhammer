jQuery(function($){
    $(document).ready(function(){

        const urlSearchParams = new URLSearchParams(window.location.search);
        const params = Object.fromEntries(urlSearchParams.entries());
        if(params?.min_year || params?.mileage || params?.min_price || params?.car_transmission || params?.body_style){
            setTimeout(() => {
                $('#filter-form').trigger('submit');
            }, 10);
        }

        $(document).on('click','.clear_filter', function(){
            var u = new URL(window.location.href);
            u.hash = ''
            u.search = ''
            window.location.href = u.toString();
        })

        // Range script Start
        updateView = function () {
            if (jQuery(this).hasClass('min-range')) {
                jQuery(this).prev().html(`${this.value}`);
                console.log(jQuery(this).parent().find('.incl-range'));
            } else {
                jQuery(this).next().html(`${this.value}`);
            }
            // if (parseInt(minRG.val()) > parseInt(maxRG.val())) {
            //     jQuery(this).parent().find('.incl-range').css('width', (maxRG.val()) / jQuery(this).attr('max') * 100 + '%')
            //     jQuery(this).parent().find('.incl-range').css('left', minRG.val() / jQuery(this).attr('max') * 100 + '%')
            //     // inclRange.style.width = (minRG.val() - maxRG.val()) / jQuery(this).attr('max') * 100 + '%';
            //     // inclRange.style.left = maxRG.val() / jQuery(this).attr('max') * 100 + '%';
            // } else {
            //     jQuery(this).parent().find('.incl-range').css('width', (minRG.val()) / jQuery(this).attr('max') * 100 + '%')
            //     jQuery(this).parent().find('.incl-range').css('left', minRG.val() / jQuery(this).attr('max') * 100 + '%')
    
            //     // inclRange.style.width = (maxRG.val() - minRG.val()) / jQuery(this).attr('max') * 100 + '%';
            //     // inclRange.style.left = minRG.val() / jQuery(this).attr('max') * 100 + '%';
            // }
        };
    
        jQuery('input[type="range"]').each(function() {
            updateView.call(this);
            jQuery(this).on('mouseup', function() {
                this.blur();
            }).on('mousedown input', function () {
                updateView.call(this);
            });
        });
    
    
        // Range script End
        jQuery(document).on('change', '.filter_change', function(){
            $('#filter-form').trigger('submit');
        })
        jQuery(document).on('submit', '#auction-search-form', function(e){
            e.preventDefault();
            jQuery('.all_products').html("<h3>Please Wait...</h3>");
            jQuery('.pagination-wrap').css('display', 'none');
            jQuery('.total_results').css('display', 'none');
            let auction_search       = $('[name="auction_search"]').val();
            auction_search           = JSON.stringify(auction_search);
            let expired              = false;
            
            if($('[name="expired"]').length > 0){
                expired = true;
            }

            let formdata = new FormData();
            formdata.append("action", "product_filters_action");
            formdata.append("auction_search", auction_search);
            formdata.append("filter_request", true);
            formdata.append("expired", expired);
            jQuery.ajax({
                type: "post",
                data: formdata,
                // dataType:"json",
                url: opt.ajaxUrl,
                success: function (msg) {
                    msg = JSON.parse(msg);
                    jQuery('.product_listing').html(msg.html);
                    ajaxSuccess = true;
                },
                cache: false,
                contentType: false,
                processData: false,
            });
            
        })
        jQuery(document).on('submit', '#filter-form', function(e){
            e.preventDefault();
            jQuery('.all_products').html("<h3>Please Wait...</h3>");
            jQuery('.pagination-wrap').css('display', 'none');
            jQuery('.total_results').css('display', 'none');
            
            let body_style   = [];
            let car_transmission = [];
            let mileage              = [];
            let min_year             = $('[name="min_year"]').val();
            let max_year             = $('[name="max_year"]').val();
            let min_price            = $('[name="min_price"]').val();
            let max_price            = $('[name="max_price"]').val();
            let expired              = false;
            if($('[name="expired"]').length > 0){
                expired = true;
            }
           
            jQuery("input:checkbox[name=body_style]:checked").each(function(){
                body_style.push(jQuery(this).val());
            });
            jQuery("input:checkbox[name=car_transmission]:checked").each(function(){
                car_transmission.push(jQuery(this).val());
            });
            jQuery("input:checkbox[name=mileage]:checked").each(function(){
                let mil_val = jQuery(this).val().split("-");
                mileage.push(mil_val[0]);
                mileage.push(mil_val[1]);
            });
            body_style              = JSON.stringify(body_style);
            car_transmission        = JSON.stringify(car_transmission);
            mileage                 = JSON.stringify(mileage);
            min_year                = JSON.stringify(min_year);
            max_year                = JSON.stringify(max_year);
            min_price               = JSON.stringify(min_price);
            max_price               = JSON.stringify(max_price);
            
            let formdata = new FormData();
            formdata.append("action", "product_filters_action");
            formdata.append("body_style", body_style);
            formdata.append("car_transmission", car_transmission);
            formdata.append("mileage", mileage);
            formdata.append("min_year", min_year);
            formdata.append("max_year", max_year);
            formdata.append("min_price", min_price);
            formdata.append("max_price", max_price);
            formdata.append("filter_request", true);
            formdata.append("expired", expired);
            jQuery.ajax({
                type: "post",
                data: formdata,
                // dataType:"json",
                url: opt.ajaxUrl,
                success: function (msg) {
                    msg = JSON.parse(msg);
                    jQuery('.product_listing').html(msg.html);
                    ajaxSuccess = true;
                },
                cache: false,
                contentType: false,
                processData: false,
            });
        });
    })
})