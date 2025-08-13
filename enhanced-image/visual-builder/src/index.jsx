// External library dependencies.
import React, { useEffect, useState } from 'react';

// TEST ALERT - if you see this, JavaScript is loading
alert('ENHANCED IMAGE MODULE: JavaScript file is loading!');

// TEST CONSOLE LOG - check browser console for this
console.log('ENHANCED IMAGE MODULE: Top level console log - file is being parsed!');

// TEST DIVI OBJECTS - check what's available
console.log('ENHANCED IMAGE MODULE: Testing Divi objects...');
console.log('window.divi:', window?.divi);
console.log('window.divi?.module:', window?.divi?.module);
console.log('window.divi?.moduleLibrary:', window?.divi?.moduleLibrary);
console.log('window.wp:', window?.wp);
console.log('window.vendor:', window?.vendor);

// WordPress/Divi package dependencies - get hooks for WordPress actions
const hooks = window?.vendor?.wp?.hooks || window?.wp?.hooks || {};
const { addAction } = hooks; // Extract addAction function from hooks
// Get API fetch function from Divi, WordPress, or vendor namespace
const apiFetch = (window?.divi?.rest?.apiFetch) || (window?.wp?.apiFetch) || (window?.vendor?.wp?.apiFetch) || null;

// Divi module APIs - extract required components from window.divi.module
const { ModuleContainer, StyleContainer, elementClassnames } = window?.divi?.module;
// Get module registration function from Divi module library
const { registerModule } = window?.divi?.moduleLibrary;

// Import module metadata configuration from JSON file
import metadata from './module.json';

// React component for rendering module styles in Visual Builder
const ModuleStyles = ({ elements, settings, mode, state, noStyleTag }) => (
  <StyleContainer mode={mode} state={state} noStyleTag={noStyleTag}>
    {/* Render styles for module container */}
    {elements.style({ attrName: 'module', styleProps: { disabledOn: { disabledModuleVisibility: settings?.disabledModuleVisibility } } })}
    {/* Render styles for image element */}
    {elements.style({ attrName: 'image' })}
    {/* Render styles for caption element */}
    {elements.style({ attrName: 'caption' })}
    {/* Render styles for description element */}
    {elements.style({ attrName: 'description' })}
  </StyleContainer>
);

// React component for registering module script data
const ModuleScriptData = ({ elements }) => (
  <React.Fragment>{elements.scriptData({ attrName: 'module' })}</React.Fragment>
);

// Function for adding module classnames based on decoration attributes
const moduleClassnames = ({ classnamesInstance, attrs }) => {
  classnamesInstance.add(
    elementClassnames({ attrs: attrs?.module?.decoration ?? {} })
  );
};

// React component for rendering placeholder icon when no image is selected
const InlinePlaceholderIcon = () => (
  <svg width="64" height="64" viewBox="0 0 64 64" xmlns="http://www.w3.org/2000/svg" style={{ opacity: 0.3 }}>
    <path fill="#f3f3f3" d="M 8 0 L 56 0 C 60.418278 0 64 3.581722 64 8 L 64 56 C 64 60.418278 60.418278 64 56 64 L 8 64 C 3.581722 64 0 60.418278 0 56 L 0 8 C 0 3.581722 3.581722 0 8 0 Z"/>
    <path fill="#a3a3a3" d="M 8 44 L 20 28 L 32 44 L 44 20 L 56 44 L 8 44 Z"/>
    <path fill="#6ec92f" fillRule="evenodd" stroke="#6ec92f" strokeWidth="4" strokeLinecap="round" strokeLinejoin="round" d="M 60 12.953491 L 53.953491 12.953491 L 53.953491 19 L 51.046509 19 L 51.046509 12.953491 L 45 12.953491 L 45 10.046509 L 51.046509 10.046509 L 51.046509 4 L 53.953491 4 L 53.953491 10.046509 L 60 10.046509 L 60 12.953491 Z"/>
  </svg>
);

// Helper function for extracting filename stem from URL to search for media by filename
function getFilenameStem(url) {
  try {
    const u = new URL(url, window.location.origin); // Create URL object from string
    const base = u.pathname.split('/').pop() || ''; // Get filename from path
    return base.replace(/\.[^/.]+$/, ''); // Remove file extension, return stem
  } catch (e) { return ''; } // Return empty string if URL parsing fails
}

// Main module definition object
const enhancedImageModule = {
  metadata, // Module metadata from JSON file
  renderers: {
    // Edit function - renders module in Visual Builder editor
    edit: ({ attrs, id, name, elements }) => {
      console.log('Debug: edit function started');
      
      // Extract image data from module attributes
      console.log('Debug: about to extract imageValue');
      const imageValue = attrs?.image?.innerContent?.desktop?.value || {};
      console.log('Debug: imageValue extracted:', imageValue);
      
      // Check if image has a source URL
      console.log('Debug: about to check hasImage');
      const hasImage = !!imageValue?.src;
      console.log('Debug: hasImage result:', hasImage);
      
      // Get image ID from various possible attribute locations
      console.log('Debug: about to extract imageId');
      let imageId = imageValue?.id || imageValue?.attachmentId || imageValue?.attachment_id || null;
      console.log('Debug: imageId result:', imageId);
      
      // Check if caption display is enabled in module settings
      console.log('Debug: about to check showCaption');
      const showCaption = attrs?.module?.advanced?.showCaption?.desktop?.value === 'on';
      console.log('Debug: showCaption result:', showCaption);
      
      // Check if description display is enabled in module settings
      console.log('Debug: about to check showDescription');
      const showDescription = attrs?.module?.advanced?.showDescription?.desktop?.value === 'on';
      console.log('Debug: showDescription result:', showDescription);

      // Debug 2: Use constant values instead of React hooks
      console.log('Debug: about to create debug constants');
      const mediaCaption = 'My Caption'; // Debug 2
      console.log('Debug: mediaCaption created:', mediaCaption);
      const mediaDescription = 'My Description'; // Debug 2
      console.log('Debug: mediaDescription created:', mediaDescription);

      console.log('Debug: edit function returning JSX with caption:', mediaCaption, 'and description:', mediaDescription);

      // Return JSX for module rendering
      console.log('Debug: about to return JSX');
      return (
        <ModuleContainer
          attrs={attrs}
          elements={elements}
          id={id}
          moduleClassName="enhanced_image_module"
          name={name}
          scriptDataComponent={ModuleScriptData}
          stylesComponent={ModuleStyles}
          classnamesFunction={moduleClassnames}
        >
          {/* Render module style components */}
          {elements.styleComponents({ attrName: 'module' })}
          {/* Main module content container */}
          <figure className="enhanced_image_module_inner">
            {/* Conditional rendering: show image if available, placeholder if not */}
            {hasImage ? (
              elements.render({ attrName: 'image' })
            ) : (
              <div className="enhanced_image_module_placeholder" style={{ display: 'flex', alignItems: 'center', justifyContent: 'center', padding: '24px' }}>
                <InlinePlaceholderIcon />
              </div>
            )}
            {/* Conditional rendering: show caption if enabled and available */}
            {showCaption && (
              <figcaption className="enhanced_image_module_caption">
                {mediaCaption ? (
                  <span dangerouslySetInnerHTML={{ __html: mediaCaption }} />
                ) : null}
              </figcaption>
            )}
            {/* Conditional rendering: show description if enabled and available */}
            {showDescription && (
              <div className="enhanced_image_module_description">
                {mediaDescription ? (
                  <div dangerouslySetInnerHTML={{ __html: mediaDescription }} />
                ) : null}
              </div>
            )}
          </figure>
        </ModuleContainer>
      );
    },
  },
  // Default content for module when first added
  placeholderContent: {
    module: { advanced: { showCaption: { desktop: { value: 'off' } }, showDescription: { desktop: { value: 'off' } } } },
    image: { innerContent: { desktop: { value: { src: '', alt: 'Enhanced Image', linkUrl: '', linkTarget: 'off' } } }, advanced: { lightbox: { desktop: { value: 'off' } }, overlay: { desktop: { value: { use: 'off' } } } } },
  },
};

// Register module if Divi module library is available
if (window?.divi?.moduleLibrary?.registerModule) {
  try { registerModule(enhancedImageModule.metadata, enhancedImageModule); } catch (e) { /* noop */ }
}

// Register module in Visual Builder using WordPress hooks
if (addAction) {
  addAction('divi.moduleLibrary.registerModuleLibraryStore.after', 'enhancedImageModule.register', () => {
    try { registerModule(enhancedImageModule.metadata, enhancedImageModule); } catch (e) { /* noop */ }
  });
}
