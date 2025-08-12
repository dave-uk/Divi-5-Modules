# Divi 5 Enhanced Image Module

A Divi 5-native module that extends the standard Image module with optional Caption and Description support from the Media Library, plus custom override capabilities.

## Features

- **Image Support**: Full image upload, linking, and lightbox functionality
- **Optional Caption**: Toggle to display the Media Library caption or custom text
- **Optional Description**: Toggle to display the Media Library description or custom HTML content
- **Custom Overrides**: Override Media Library content with custom caption and description
- **Image Placeholder**: Visual placeholder when no image is selected
- **Full Design Controls**: Complete styling options in the Design tab
- **Responsive**: Mobile-friendly design
- **Security**: Sanitized HTML output for safe content display

## Installation

1. Upload the plugin to your WordPress site
2. Activate the plugin
3. The "Enhanced Image" module will appear in your Divi 5 Visual Builder

## Usage

### Adding the Module

1. Open the Divi Visual Builder
2. Click the "+" button to add a new module
3. Search for "Enhanced Image" and select it

### Configuration

#### Content Tab
- **Image**: Upload or select an image from your Media Library
- **Image Link URL**: Optional link for the image
- **Image Link Target**: Choose between same tab or new tab
- **Image Alternative Text**: Alt text for accessibility
- **Show Caption**: Toggle to display caption (Media Library or custom)
- **Show Description**: Toggle to display description (Media Library or custom)
- **Custom Caption**: Override Media Library caption with custom text
- **Custom Description**: Override Media Library description with custom HTML content

#### Design Tab
- **Caption**: Font styling for the caption text
- **Description**: Font styling for the description text
- **Module**: Background, border, spacing, and other design options
- **Image**: Border and shadow effects for the image

#### Advanced Tab
- **Lightbox**: Enable lightbox functionality
- **Image Overlay**: Hover effects and overlay styling
- **Attributes**: Additional image attributes

### Content Priority

The module follows this priority for caption and description content:

1. **Custom Override**: If you enter custom caption/description, it takes priority
2. **Media Library**: If no custom content, falls back to Media Library content
3. **Hidden**: If toggles are off, content is hidden regardless of source

### Media Library Integration

The module automatically pulls caption and description from your Media Library when no custom override is provided:

1. **Caption**: Set in the Media Library under "Caption" field
2. **Description**: Set in the Media Library under "Description" field

Both fields support HTML content which will be safely sanitized when displayed.

### Image Placeholder

When no image is selected, the module displays a styled placeholder with:
- Camera icon (📷)
- "Click to add an image" text
- Dashed border styling
- Responsive design

## Technical Details

- **Module Name**: `d5-tut/enhanced-image-module`
- **CSS Class**: `d5_tut_enhanced_image_module`
- **Divi 5 Compatible**: Yes
- **WordPress Version**: 5.0+
- **Divi Version**: 5.0+

## Security Features

- All HTML content is sanitized using `wp_kses_post()`
- Description content is processed through `wpautop()` for proper paragraph formatting
- No direct user input is printed without sanitization
- Custom HTML content is safely processed and displayed

## Customization

The module uses standard Divi design controls, so all existing Divi themes and child themes will work seamlessly. You can customize the appearance using:

- Divi Theme Options
- Custom CSS
- Child theme modifications
- Divi Visual Builder design controls

## Support

For issues or questions, please check:
1. WordPress error logs
2. Browser console for JavaScript errors
3. Divi 5 compatibility
4. Plugin conflicts

## Changelog

### Version 1.0.0
- Initial release
- Enhanced Image module with caption and description support
- Custom caption and description override fields
- Image placeholder when no image is selected
- Full Divi 5 integration
- Complete design control system
