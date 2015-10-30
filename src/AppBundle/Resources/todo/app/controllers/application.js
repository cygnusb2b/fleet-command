import Ember from 'ember';

export default Ember.Controller.extend({
    actions: {
        create: function() {
            var todo = this.store.createRecord('todo', {
                name: this.get('name'),
                description: this.get('description'),
            });
            todo.save();
        }
    }
});
