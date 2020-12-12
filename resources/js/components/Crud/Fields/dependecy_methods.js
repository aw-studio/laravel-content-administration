export default {
    /**
     * Resolve depencies.
     *
     * @param  {array}     dependencies
     * @return {undefined}
     */
    resolveDependecies(dependencies) {
        if (!dependencies || !Array.isArray(dependencies)) {
            return;
        }

        let fulfilled = true;
        for (let i in dependencies) {
            let dependency = dependencies[i];

            if (typeof dependency != 'object') {
                continue;
            }

            if (!('condition' in dependency)) {
                continue;
            }

            let conditionMethod = this.getDependencyConditionMethod(dependency);

            if (!conditionMethod) {
                continue;
            }

            let conditionFulfilled = conditionMethod(dependency);

            if (dependency.condition.startsWith('or') && !conditionFulfilled) {
                continue;
            }

            fulfilled = conditionFulfilled;
        }

        this.fulfillsConditions = fulfilled;
    },

    /**
     * Get dependency condition method.
     */
    getDependencyConditionMethod(dependency) {
        return this[
            'fulfills' + dependency.condition.replace('or', '').capitalize()
        ];
    },

    /**
     * Determines if when condition is fulfilled.
     */
    fulfillsWhen(dependency) {
        return this.model[dependency.attribute] == dependency.value;
    },

    /**
     * Determines if whenNot condition is fulfilled.
     */
    fulfillsWhenNot(dependency) {
        return this.model[dependency.attribute] != dependency.value;
    },

    /**
     * Determines if whenContains condition is fulfilled.
     */
    fulfillsWhenContains(dependency) {
        let value = this.model[dependency.attribute];

        if (!value) {
            return;
        }

        if (typeof value == 'string' || typeof value == 'array') {
            return value.includes(dependency.value);
        }
    },

    /**
     * Determines if whenIn condition is fulfilled.
     */
    fulfillsWhenIn(dependency) {
        const value = this.model[dependency.attribute];

        const expected = dependency.value;

        if (typeof expected === 'string' || typeof expected === 'array') {
            return expected.includes(value);
        }
    },
};
