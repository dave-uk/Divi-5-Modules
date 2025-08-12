// Register this module with server-preview (no custom edit renderer).
import metadata from './module.json';

const { addAction } = (window?.vendor?.wp?.hooks || window?.wp?.hooks || {});
const { registerModule } = window?.divi?.moduleLibrary || {};

const enhancedImageModule = { metadata };

if (registerModule) {
  try { registerModule(enhancedImageModule.metadata, enhancedImageModule); } catch (e) {}
}
if (addAction) {
  addAction('divi.moduleLibrary.registerModuleLibraryStore.after', 'enhancedImageModule.register', () => {
    try { registerModule(enhancedImageModule.metadata, enhancedImageModule); } catch (e) {}
  });
}