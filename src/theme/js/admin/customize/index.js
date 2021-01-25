import $ from 'jquery';
import React from 'react';
import ReactDOM from 'react-dom';
import ObjectSelector from './object-selector';
import CustomList from './custom-list';

const CLASS_OBJ_MAP = {
	object_selector: ObjectSelector,
	custom_list: CustomList,
};

if (wp && wp.customize) {
	wp.customize.bind('ready', () => {
		Object.keys(CLASS_OBJ_MAP).forEach(clsPart => {
			$(`.customize-control.customize-control-${clsPart}`).each(function () {
				const $this = $(this);
				const key = $this.attr('id').substring(18); //customize-control-

				const Element = CLASS_OBJ_MAP[clsPart];

				wp.customize.control(key, function (control) {
					const container = control.container.find('.root-wrapper').get(0);
					ReactDOM.render(
						<Element control={control}
								 onChange={(values) => control.setting.set(values)}
						/>, container
					);
				});

			});
		});
	});
}
