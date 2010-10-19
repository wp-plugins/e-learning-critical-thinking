jQuery(document).ready(function(){

    jQuery("#new_textbox").live("click",function(){
    
        var fieldset = jQuery(".elearning_fieldset:first").clone();
        

        jQuery(fieldset).find("input").each(function(){
            jQuery(this).attr("value","")

        })

        jQuery(fieldset).find("textarea").each(function(){
            jQuery(this).html("")

        })

        jQuery(fieldset).find("select").each(function(){
            jQuery(this).val("Question")

        })
      
        jQuery(".elearning_fieldset:last").after(fieldset);
        return false;
    })

    jQuery(".delete_elearning_item").live("click",function(){

        if (jQuery(".elearning_fieldset").length > 1) {
             jQuery(this).parents(".elearning_fieldset").remove()
        } else {

                var fieldset = jQuery(this).parents(".elearning_fieldset");
                
                jQuery(fieldset).find("input").each(function(){
                    jQuery(this).attr("value","")

                })

                jQuery(fieldset).find("textarea").each(function(){
                    jQuery(this).val("")
                    //console.log(jQuery(this))

                })

                jQuery(fieldset).find("select").each(function(){
                    jQuery(this).val("Question")

                })

        }
       
        return false;
    })

})