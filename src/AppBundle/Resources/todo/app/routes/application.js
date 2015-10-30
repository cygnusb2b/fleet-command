import Ember from 'ember';

export default Ember.Route.extend({
    model: function() {
        return this.store.findAll('todo');
    },
    actions: {
        toggle: function(todo) {
            todo.set('complete', !todo.get('complete'));
            todo.save();
        }
    }
});
