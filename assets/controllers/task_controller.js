import { Controller } from '@hotwired/stimulus';
import Sortable from 'sortablejs';

export default class extends Controller {
    static targets = ['column'];

    connect() {
        this.columnTargets.forEach(column => {
            Sortable.create(column, {
                group: 'task',
                animation: 150,
                onEnd: this.handleDrop.bind(this)
            });
        });
    }

    async handleDrop(event) {
        const taskId = event.item.id.replace('task_', '');
        const oldStatus = event.from.dataset.columnId;
        const newStatus = event.to.dataset.columnId;

        if (oldStatus !== newStatus) {
            const formData = new FormData();
            formData.append('status', newStatus);

            await fetch(`/task/${taskId}/move`, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                }
            });
        }
    }
}
