jQuery( document ).ready( function( $ )
{
	$( "#loginform" ).css( 'position', 'relative' );

	$( "#wp-submit" ).click( function( event )
	{
		event.preventDefault();

		$( "#loginform" ).addClass( 'updating' );

		$( ".notice.message" ).remove();

		var redirect = jQuery( "input[name='redirect_to'" ).val() == '' ? window.location.origin : jQuery( "input[name='redirect_to'" ).val();

		var data =
		{
			'action'			: 'wpla_login_ajax',
			'login'				: $( "input#user_login" ).val(),
			'pass'				: $( "input#user_pass" ).val(),
			'rememberme'		: '',
			'wpla_login_nonce'	: $( '#wpla_login_nonce' ).val()
		};

		if ( $( "input#rememberme" ).is( ':checked' ) )
		{
			data.rememberme = $( "input#rememberme" ).val();
		}

		$.ajax( {
			url 	: WpLoginAjaxify.ajaxurl,
			type 	: 'POST',
			data 	: data,
		} )
		.done( function( response )
		{
			if ( response.wp_error )
			{
				$( "form#loginform" ).before( '<div id="login_error" class="notice notice-error message">' + response.wp_error + '</div>' );
			}
			else if( response.wp_success )
			{
				$( "form#loginform input" ).prop( 'disabled', true );

				$( "form#loginform" ).before( '<div id="login_success" class="notice notice-success message">' + response.wp_success + '</div>' );
				
				window.location.href 	= redirect;
			}
		} )
		.fail( function( jqXHR, textStatus )
		{
			$( "form#loginform" ).before( '<div id="login_error" class="notice notice-error message">' + WpLoginAjaxify.failedMsg + '</div>' );
		} )
		.always( function()
		{
			$( "#loginform" ).removeClass( 'updating' );
		} );
	} );
} );