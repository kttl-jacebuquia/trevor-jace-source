export interface ActiveFiltersState {
	location?: string;
	date?: string;
	type?: string;
}

export interface EventsGridStateType {
	activeFilters: ActiveFiltersState;
	page: number;
}
