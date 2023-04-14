<template>
    <!-- 
        https://stackoverflow.com/questions/54946716/how-do-i-loop-and-create-a-menu-with-multiple-sub-menu-levels-in-vue
        https://medium.com/js-dojo/programmatic-menus-in-vuejs-61fa8024cf9d
     -->
    <li :class="(type == 'title') ? `menu-title` : ``">
        <span v-if="type == 'title'">{{name}}</span>
        <router-link  v-else :to="url" class="side-nav-link-ref">
            <i :class="(typeof icon === null) ? null : icon" v-if="typeof icon != null"></i> <span>{{name}}</span>
        </router-link>
        
        <ul v-if="childrens.length > 0">
            <Menu 
                v-for="(menu, index) in childrens" :key="index"
                :name="menu.name" 
                :url="menu.url" 
                :childrens="menu['childrens']">
            </Menu>
        </ul>
    </li>
</template>
<script>
export default {
    name:"Menu",
    props: {
        name: {type:String},
        icon: {type:String},
        url: {type:String},
        type: {type:String},
        childrens: {required:true}
    },
    created(){
        console.log(type)
    }
}
</script>