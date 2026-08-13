import Alpine from 'alpinejs';
import { registerCommentsComponent } from './comments-component';
import { registerCollaboratorComponent } from './collaborator-component';
import { registerIdeaAccessComponent } from './idea-access-component';

/**
 * Echo exposes an expressive API for subscribing to channels and listening
 * for events that are broadcast by Laravel. Echo and event broadcasting
 * allow your team to quickly build robust real-time web applications.
 */
import './echo';

window.Alpine = Alpine;

registerCommentsComponent(Alpine);
registerCollaboratorComponent(Alpine);
registerIdeaAccessComponent(Alpine);

Alpine.start();
