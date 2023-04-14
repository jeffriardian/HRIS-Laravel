import general from './modules/general'
import utilities from './modules/utilities'
import engineering from './modules/engineering'
import humanResource from './modules/human-resource'

var modules = _.merge({
    general,
    utilities,
    engineering,
    humanResource
});

export default modules;