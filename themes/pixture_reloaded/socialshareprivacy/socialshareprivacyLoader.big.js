	jQuery(document).ready(function($){

		$('.socialshareprivacy').socialSharePrivacy({
				uri : function(context){
					return $(context).parent().data('url');
				},
				css_path : "/themes/pixture_reloaded/socialshareprivacy/socialshareprivacy.css",
				services : {
					facebook : {
						 'dummy_img'         : "/themes/pixture_reloaded/socialshareprivacy/images/dummy_facebook.png"
					},
					twitter : {
						'dummy_img'         : "/themes/pixture_reloaded/socialshareprivacy/images/dummy_twitter.png",
						tweet_text : function(context){
							return $(context).parent().data('text');
						},
						'via'								: "BankingCheck",
					},
					gplus : {
						'dummy_img'         : "/themes/pixture_reloaded/socialshareprivacy/images/dummy_gplus.png"
					}
					
				}				
		}); 

	});