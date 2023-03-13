export default function isImage(file: File) : boolean {
	const supportedImageTypes = [
		'image/gif',
		'image/png',
		'image/jpeg',
		'image/bmp',
		'image/webp',
		'image/svg+xml',
	];

	return file.type.indexOf('image/') === 0 && supportedImageTypes.includes(file.type);
}
