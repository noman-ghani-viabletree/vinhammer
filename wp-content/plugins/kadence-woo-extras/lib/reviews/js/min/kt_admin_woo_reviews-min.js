function KadenceConvertButton(){if($button=jQuery("#kt-review-convert"),!$button.prop("disabled")){$button.prop("disabled",!0),$button.find(".spinner-item").addClass("spinner"),$button.find(".spinner-item").addClass("is-active");var n={action:"kt_review_convert",security:kadenceAdminReviews.ajax_nonce};jQuery.post(ajaxurl,n,(function(n){$button.find(".spinner-item").removeClass("spinner"),$button.find(".spinner-item").removeClass("is-active"),jQuery(".convert-info p").append(n.value),$button.prop("disabled",!1)}))}}jQuery((function($){function n(){$("#kadence-convert-info").html('<button id="kt-review-convert" class="button-primary kt-review-convert" onClick="KadenceConvertButton();"style="margin:10px 0;">Convert Reviews<span class="spinner-item"></span></button><p></p>')}function t(){$(".kadence-settings-component-kt_reviews input").on("change",(function(){setTimeout((function(){n()}),300)}))}setTimeout((function(){n(),t()}),300),$(".kadence-settings-dashboard-section-tabs > .components-tab-panel__tabs button").each((function(){$(this).on("click",(function(){setTimeout((function(){n(),t()}),300)}))}))}));