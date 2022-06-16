export interface ClassyEvent {
	"status": string,
	"channel_keywords": string,
	"timezone_identifier": string|null,
	"contact_phone": string,
	"location_details": "",
	"id": number,
	"name": string|null,
	"started_at": string|null,
	"venue": string|null,
	"created_at": string|null,
	"postal_code": string|null,
	"address1": string|null,
	"city": string|null,
	"state": string|null,
	"country": string|null,
	"contact_email": string|null,
	"type": string|null,
	"organization_id": number,
	"is_fees_free": false,
	"logo_id": string|number|null,
	"logo_url": string|null,
	"team_cover_photo_id": string|number|null,
	"team_cover_photo_url": string|null,
	"canonical_url": string;
}

export interface WithFilterValues {
	locationFilter: string;
	dateFilter: string;
	typeFilter: string;
}
