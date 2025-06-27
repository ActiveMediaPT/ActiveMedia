wp.domReady( () => {
	wp.blocks.registerBlockStyle('core/image', [ 
		{
			name: 'semplice-img-fullwidth',
			label: 'Fullwidth',
		},
		{
			name: 'semplice-img-oversize-1',
			label: 'Oversize (100px)',
		},
		{
			name: 'semplice-img-oversize-2',
			label: 'Oversize (200px)',
		},
		{
			name: 'semplice-img-oversize-3',
			label: 'Oversize (300px)',
		},
		{
			name: 'semplice-img-oversize-4',
			label: 'Oversize (400px)',
		}
	]);
	// custom typography styles
	if(typeof sempliceGutenberg.customStyles == 'object') {
		jQuery.each(sempliceGutenberg.customStyles, function(id, name) {
			wp.blocks.registerBlockStyle('core/paragraph', [ 
				{
					name: id,
					label: name,
				}
			]);
		});
	}
} );