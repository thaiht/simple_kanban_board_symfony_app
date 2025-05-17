import './bootstrap.js';
/*
 * Welcome to your app's main JavaScript file!
 *
 * This file will be included onto the page via the importmap() Twig function,
 * which should already be in your base.html.twig.
 */
import './styles/app.css';

// Bootstrap
import 'bootstrap/dist/css/bootstrap.min.css';
import 'bootstrap';

import { Application } from '@hotwired/stimulus';

const app = Application.start();

// Stimulus controller registration
import TaskController from './controllers/task_controller.js';
app.register('task', TaskController);
import ModalController from './controllers/modal_controller.js';
app.register('modal', ModalController);
