import Ember from 'ember';

export default Ember.Component.extend({
    actions: {
        toggle: function(todo) {
            todo.set('complete', !todo.get('complete'));
            todo.save();
        }
    }
});
