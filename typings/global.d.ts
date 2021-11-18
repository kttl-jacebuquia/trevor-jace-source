export declare global {
	interface Window {
		dataLayer: any[];
		Map: any; // TODO: Replace with an actual Map interface
	}

	interface FormAssemblyElement extends HTMLElement {
		querySelectorAll: (selector: string) => FormAssemblyElementCollection;
	}

	interface FormAssemblyElementCollection extends Array<FormAssemblyElement> {

	}
}
