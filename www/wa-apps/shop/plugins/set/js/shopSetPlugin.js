(function($){

$.shopSetPlugin = {
	options:[],
	init: function(options){
		this.options = options;
		this.initComplect();
		this.cartInit();
	},
	initComplect: function(){
		var cblock = $('#shop-set-plugin-complect-block'),
			self = this;
		if ( cblock.size() ){
			cblock.on('click','button',function(){

				var b = $(this),
					sku_id = b.data('sku-id'),
					set_id = b.data('set-id');

				if ( sku_id =='' ) { console.log('empty sku_id'); return false; }


				$.post(self.options.url+'shop-set-plugin-cart-add/',{ set_id:set_id,sku_id:sku_id },function(response){
					if ( !response.data.available ){
						b.hide();
						b.next('.set-error').show();
					}else{
						if ( typeof self.options.afterAddCompect === 'function' )
							self.options.afterAddCompect(response);
						var s = b.closest('div').find('.set-success');
						s.show();
						setTimeout(function(){
							s.hide();
						},3000);
					}
				},'json');
			});
		}
	},
	cartInit: function(){
		var self = this,
			url = self.options.url;
		this.setsInCart();
		$('body')
		.on('change','.box-modal .set-cart-item input',function(){
			var that = $(this),
				p = {
					set_id:that.data('set-id'),
					quantity:that.val()
				};
			$.post(url+'shop-set-plugin-cart-save/',p,function(r){
				that.next('.cart-item-total').html(r.data.item_total);
				that.val(r.data.quantity);
				that.closest('.set-cart-item').find('.set-delete').show();
			},'json')
		})
		.on('click','.set-delete',function(){
			var set_id = $(this).data('set-id');
			$.post(url+'shop-set-plugin-cart-save/',{ set_id:set_id,quantity:0 },function(){
				$.arcticmodal('close');
			},'json')
			return false;
		})
		.on('click','#shop-set-plugin-complect-block button',function(){
			$.post(url+'shop-set-plugin-cart-add/',{ set_id:$(this).data('set-id') },function(){
				$.arcticmodal('close');
			},'json');
		})
		.on('change','.set_qty',function(){
			var q = $(this).prev('input');
				v = $(this).val();
			if ( v >= $(this).data('quantity') )
				q.val(v)//.trigger('change');
			else{
				if ( confirm('Уменьшение количества приведет к удалению комплекта') )
					q.val(v)//.trigger('change');
				else
					$(this).attr('value',q.val());
			}
		})
		.on('click','.set-cart-item-info a',function(){
			$.post(url+'shop-set-plugin-set/',{ set_id:$(this).data('id') },function(response){
				$(response).arcticmodal({
					afterClose: function(){
						window.location.reload();
						// self.updateCart();
					}
				});
			});
			return false;
		});
	},
	complectSwitch: function(sku_id){
		var cblock = $('#shop-set-plugin-complect-block');
		if ( sku_id > 0 ){
			$('.sku-sets',cblock).hide();
			$('#sku-sets-'.sku_id).show();
		}
	},
	setsInCart: function(){
		var o = this.options;
		$('.set-cart-item-info').remove();
		$('.set_qty').data('quantity',0).attr('quantity',0);
		$('.set-cart-item-tmpl').each(function(){
			var item_id = $(this).data('id'),
				e = $($(this).html());
				q = $(o.cart_item_qty_selector.replace('ITEMID',item_id));
			$(o.cart_item_selector.replace('ITEMID',item_id)).append(e);
			if ( q.next('.set_qty').size() == 0 )
				q.after( q.clone().data('quantity',0).addClass('set_qty') ).hide();
			
			var n = q.next('.set_qty')
				qty = parseInt(n.data('quantity')),
				set_qty = parseInt(e.data('set-item-quantity'));
			qty += set_qty;
			n.data('quantity',qty).attr('quantity',qty);
			$(this).remove();
		});
	},
	updateCartSets: function(){
		var self = this;
		$.get(this.options.url+'shop-set-plugin-cart-sets/',function(r){
			$('#shop-set-plugin-cart-wr').replaceWith($(r));
			self.setsInCart();
		});
	},
	updateCart: function(){

		console.log("--updateCart--");
		var self = this,
			o = '.second-page-container form',
			url = this.options.url+'cart/ form';
		$(o).load(url,function(){
			self.updateCartSets();
		});
	}
}

})(jQuery);