///**----------------------------------------------------------------------------------- add response **/
        $("body").delegate(".add-another-quiz_response", "click", function (e) {
            var responseCount = jQuery('.quiz_response-container').length;
            e.preventDefault();

            var responseList = jQuery('.quiz_responses-fields-list');

            // grab the prototype template
            var newWidget = responseList.attr('data-prototype');
            // replace the "__name__" used in the id and name of the prototype
            // with a number that's unique to your emails
            // end name attribute looks like name="contact[emails][2]"
            newWidget = newWidget.replace(/__name__/g, responseCount);
            
            responseCount++;

            // create a new list element and add it to the list
            var newLi = jQuery('<div class="quiz_response-container"></div>').html(newWidget);
            newLi.appendTo(responseList);
            

    })
    
    
/**------------------------------------------------------------add condition  **/        
    $("body").delegate(".add-another-parent_response", "click", function (e) {
    var responseCount = jQuery('.parent_question_container').length;
    e.preventDefault();

    var responseList = jQuery('#_parent_responses_view');

    // grab the prototype template
    var newWidget = responseList.attr('data-prototype');
    // replace the "__name__" used in the id and name of the prototype
    // with a number that's unique to your emails
    // end name attribute looks like name="contact[emails][2]"
    newWidget = newWidget.replace(/__name__/g, responseCount);
    
    responseCount++;

    // create a new list element and add it to the list
    var newLi = jQuery('<div class=""></div>').html(newWidget);
    newLi.appendTo(responseList);
    

});
        
/**-----------------------------------------------------------------------------***/
    function replaceAll(find, replace, str) {
    	  return str.replace(new RegExp(find, 'g'), replace);
    	}

    function deleteResponse(el){
    	el.closest(".quiz_response-container").remove();
        }
    function deleteParentResponse(el){
    	container = el.closest(".parent_question_container");
    	jQuery("input[type=checkbox][checked='checked']", $(container)).each(function(){
    		console.log($(this).parent());
    		$(this).parent().trigger("click");
    	})
    	el.closest(".parent_question_container").remove();
        }
    function deleteQuestion(el){
    	$(el).closest(".panel").remove();
		var count = 1;
    	$("#aq_questions-fields-list .panel").each(function(){
    		jQuery(".panel-heading a",this ).html("Question " + count);
			count++;
        	});

    	if($("#aq_questions-fields-list .panel").length == 0)
        	$("#res-msg").show();
        }
    
/*** ------------------------------------------------------------------------------- ***/
    $(document).ready(function(){
    	
    options = [];
    if($("#quiz_question_parent_responses").length > 0 ){
    options=   $("#quiz_question_parent_responses").val().split(',');
	$("body").delegate(".parent_question_container .dropdown-menu a", "click", function (event) {
       var $target = $( event.currentTarget ),
           val = $target.attr( 'data-value' ),
           $inp = $target.find( 'input' ),
           idx;

       if ( ( idx = options.indexOf( val ) ) > -1 ) {
          options.splice( idx, 1 );
          setTimeout( function() { $inp.prop( 'checked', false ) }, 0);
       } else {
          options.push( val );
          setTimeout( function() { $inp.prop( 'checked', true ) }, 0);
       }

       $( event.target ).blur();
       $("#quiz_question_parent_responses").val(options);
       return false;
    });
    }
    })
	
    
    
    function deleteElement(msg_delete, url, text_delete, text_cancel){
	smoke.confirm(msg_delete,function(e){
		if (e){
			window.location.href = url;
			//smoke.alert('"yeah it is" pressed', {ok:"close"});
		}else{
			//smoke.alert('"no way" pressed', {ok:"close"});
		}
	}, {ok:text_delete, cancel:text_cancel});
}
