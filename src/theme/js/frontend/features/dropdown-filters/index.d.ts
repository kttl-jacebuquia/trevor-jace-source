interface FilterOptionProp {
	value: string;
	label: string;
}

export interface DropdownFilterField {
	id: string;
	buttonLabel: string;
	allLabel: string;
}

export interface DropdownFiltersOptions {
	fields?: DropdownFilterField[];
	class?: string;
	headline?: string;
}
