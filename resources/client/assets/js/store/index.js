import Vue from 'vue'
import Vuex from 'vuex'
import Alert from './modules/alert'
import ChangePassword from './modules/change_password'
import Rules from './modules/rules'
import SurveysIndex from './modules/Surveys'
import SurveysSingle from './modules/Surveys/single'
import ClassesIndex from './modules/Classes'
import ClassesSingle from './modules/Classes/single'
import InstitutionsIndex from './modules/Institutions'
import InstitutionsSingle from './modules/Institutions/single'
import CategoriesIndex from './modules/Categories'
import CategoriesSingle from './modules/Categories/single'
import PermissionsIndex from './modules/Permissions'
import PermissionsSingle from './modules/Permissions/single'
import RolesIndex from './modules/Roles'
import RolesSingle from './modules/Roles/single'
import UsersIndex from './modules/Users'
import UsersSingle from './modules/Users/single'

Vue.use(Vuex)

const debug = process.env.NODE_ENV !== 'production'

export default new Vuex.Store({
    modules: {
        Alert,
        ChangePassword,
        Rules,
        SurveysIndex,
        SurveysSingle,
        ClassesIndex,
        ClassesSingle,
        InstitutionsIndex,
        InstitutionsSingle,
        CategoriesIndex,
        CategoriesSingle,
        PermissionsIndex,
        PermissionsSingle,
        RolesIndex,
        RolesSingle,
        UsersIndex,
        UsersSingle,
    },
    strict: debug,
})
