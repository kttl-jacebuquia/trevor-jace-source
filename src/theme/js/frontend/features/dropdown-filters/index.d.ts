interface FilterOptionProp {
	value: string;
	label: string;
}

export type FilterOptions = {[value: string]: string};

export interface DropdownFilterField {
	id: string;
	buttonLabel: string;
	allLabel: string;
	options: FilterOptions;
}

export interface DropdownFiltersOptions {
	fields?: DropdownFilterField[];
	class?: string;
	headline?: string;
	onChange?: (activeFilters: FilterOptions) => void;
	initialFilters?: FilterOptions;
}

export interface DropdownFiltersState {
	activeFilters: { [filterFieldKey: string]: string };
}
