/**
 * ----------------------------------------------------------------------------
 * Software:		eorisis framework
 * @author		eorisis http://eorisis.com
 * 
 * @copyright	Copyright (C) 2012-2014 eorisis. All Rights Reserved.
 * @license		eorisis Proprietary Use License: http://eorisis.com/license
 * ----------------------------------------------------------------------------
**/

var jq_loaded = false;
function eorisis_info()
{
	if (jq_loaded)
	{
		var head = document.getElementsByTagName("head")[0];
		script = document.createElement("script");
		script.type = "text/javascript";
		script.src = "//code.jquery.com/jquery-migrate-1.2.1.min.js";
		head.insertBefore(script, head.childNodes[1]);

		var head = document.getElementsByTagName("head")[0];
		script = document.createElement("script");
		script.type = "text/javascript";
		script.innerHTML = "jQuery.noConflict();";
		head.insertBefore(script, head.childNodes[2]);
	}

	if (typeof(jQuery) === "undefined")
	{
		var head = document.getElementsByTagName("head")[0];
		script = document.createElement("script");
		script.type = "text/javascript";
		script.src = "//code.jquery.com/jquery-1.11.0.min.js";
		head.insertBefore(script, head.firstChild);

		//	jQuery is now loaded
		jq_loaded = true;

		script.onload = function()
		{
			eorisis_info();
		};
	}
	else
	{
		jQuery(document).ready(function($)
		{
			$("#server_version").hide();
			$("#eo_dl_update").hide();
			$('#eo_latest').hide();
			$('#eo_release_date').hide();
			$("#eo_release_notes").hide();

			$("#eo_update_check a").fadeIn(500);

			function check_for_update()
			{
				$("#eo_update_check").fadeOut(500).hide();
				$("#server_version").fadeIn(500);

				$("#eo_version_no").text('Checking for update ..').fadeIn(500, function()
				{
					timer = setTimeout(function()
					{
						clearTimeout(timer);

						$.ajax(
						{
							url				: '*update_site_url*/*app_url*/version',
							type			: 'GET',
							data			: {'update':'*app_type*', 'software':'*app_url*'},
							dataType		: 'jsonp',
							jsonpCallback	: 'callback',
							contentType		: 'application/json; charset=utf-8',
							cache			: false,
							timeout			: 12000,
							success: function(response)
							{
								if ((typeof response['server_version'] !== 'undefined') &&
									(typeof response['infourl'] !== 'undefined') &&
									(typeof response['download_url'] !== 'undefined') &&
									(typeof response['release_date'] !== 'undefined') &&
									(typeof response['release_notes'] !== 'undefined'))
								{
									var server_version	= response['server_version'];
									var infourl			= response['infourl'];
									var download_url	= response['download_url'];
									var release_date	= response['release_date'];
									var release_notes	= response['release_notes'];

									$("#eo_infourl").wrap(function()
									{
										var link = $('<a/>');
										link.attr('href', infourl);
										link.attr('target', '_blank');
										link.attr('title', 'Opens in a new window');
										link.text($(this).text());
										return link;
									});

									$("#eo_version_no").fadeOut(300, function()
									{
										$("#eo_version_no").html('Latest Version: '+server_version).fadeIn(500);
										$("#eo_release").fadeIn(800);
										$('#server_version').animate({ height:"55px" }, 200, 'linear');

										if (server_version <= installed_version)
										{
											$('#eo_release').animate({ height:"40px" }, 200, 'linear');
											$('#eo_latest').text('You have the latest version').fadeIn();
											$('#eo_release_date').html('Release Date: '+release_date).fadeIn();
											$('#eo_infourl').html('Visit the software info page').fadeIn();
										}
										else if (server_version > installed_version)
										{
											$("#eo_dl_update").wrap(function()
											{
												var link = $('<a/>');
												link.attr('href', download_url);
												link.attr('target', '_blank');
												link.attr('title', 'Opens in a new window');
												link.text($(this).text());
												return link;
											});

											$('#eo_release').animate({ height:"60px" }, 200, 'linear');
											$('#eo_dl_update').fadeIn(150).fadeOut(150).fadeIn().fadeOut(150).fadeIn().text('A new version is available: Download Here');
											$('#eo_release_date').html('Release Date: '+release_date).fadeIn();
											$("#eo_release_notes").html('Release Notes: '+release_notes).fadeIn();
											$('#eo_infourl').html('Visit the software info page').fadeIn();

											$("#eo_release").addClass('new_update');
											$("span#eo_dl_update").addClass('blue');
										}
									});
								}
								else
								{
									conneciton_error();
								}
							},
							error: function()
							{
								conneciton_error();
							}
						});
						return false;

					}, 550);
				});
			}

			function conneciton_error()
			{
				$("#eo_version_no").fadeOut(function()
				{
					var title = 'Unable to connect to server';
					var desc = 'Could not get version information from the update server. The server may be offline or your browser is blocking the connection. You can try checking for an update using your Joomla Extension Manager. If this persists, please contact: info@eorisis.com';

					$('#server_version').animate({ height:'120px' }, 300, 'linear');
					$("#eo_version_no").addClass('error');
					$("#eo_dl_update").addClass('error');
					$("#eo_version_no").text(title).fadeIn();
					$('#eo_dl_update').text(desc).fadeIn();
				});
			}

			$('#eo_update_check').click(function()
			{
				check_for_update(this.href);
				return false;
			});
		});
	}
};
eorisis_info();
