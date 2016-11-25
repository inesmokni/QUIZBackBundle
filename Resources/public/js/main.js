function deleteAQ(msg_delete, url, text_delete, text_cancel){
	smoke.confirm(msg_delete,function(e){
		if (e){
			window.location.href = url;
		}else{
		}
	}, {ok:text_delete, cancel:text_cancel});
}

function getUpdateAqContent(id){
	$('.bs-example-modal-lg'+id).find('.modal-content').html('')
 $('#manual-ajax_'+id).find('.ajax-loader').show();
 $.ajax({
	 type: "POST",
	 url: $('#manual-ajax_'+id).data('url'),
	 success: function (data) {
		 $('.bs-example-modal-lg'+id).find('.modal-content').html(data);
		 $('#manual-ajax_'+id).find('.ajax-loader').hide();
	 }
 })

}