<template>
    <div>
        <input
            type="text"
            class="form-control form-control-sm form-control-sm-plus-height"
            @click="openModal"
            v-model="names"
            :disabled="disabled"
        />

        <b-modal
            ref="my-modal"
            title="List Employee"
            size="lg"
            no-close-on-backdrop
            no-close-on-esc
            hide-header-close
        >
            <div class="search-box mr-2 mb-2 d-inline-block">
                <div class="position-relative">
                    <input
                        type="text"
                        class="form-control"
                        :placeholder="$t('search')"
                        v-model="keyword"
                    />
                    <i class="bx bx-search-alt search-icon"></i>
                </div>
            </div>
            <br />
            <!-- List Selected Employee -->
            <div class="list-selected-employee">
                <div
                    v-for="(employee, index) in listSelectedEmployee"
                    :key="index"
                    class="employee"
                >
                    <span>{{ employee.nik }}</span> -
                    <span>{{ employee.name }}</span>
                    <button
                        v-if="!singleSelected"
                        class="deselect"
                        @click="deselect(index)"
                    >
                        <i class="fas fa-times"></i>
                    </button>
                </div>
            </div>
            <!-- End of List Selected Employee -->
            <!-- Table Employee -->
            <b-table
                responsive="sm"
                thead-class="thead-light"
                small
                hover
                bordered
                :items="employees"
                :fields="fields"
                :per-page="perPage"
                :current-page="currentPage"
            >
                <template v-slot:head()="data">{{ $tc(data.label) }}</template>
                <template v-slot:cell(actions)="row">
                    <input
                        type="checkbox"
                        name="employee_id"
                        @click="
                            selectedEmployee(
                                row.item.id,
                                row.item.nik,
                                row.item.name,
                                row.item.position.name
                            )
                        "
                        v-if="!singleSelected"
                        :checked="checkSelected(row.item.id)"
                    />
                    <input
                        type="radio"
                        name="employee_id"
                        @click="
                            selectedEmployee(
                                row.item.id,
                                row.item.nik,
                                row.item.name,
                                row.item.position.name
                            )
                        "
                        v-if="singleSelected"
                        :checked="checkSelected(row.item.id)"
                    />
                </template>
            </b-table>

            <b-pagination
                v-model="currentPage"
                :total-rows="rows"
                :per-page="perPage"
                pills
                class="v-step-4 float-right"
            ></b-pagination>
            <!-- End of Table Employee -->
            <template v-slot:modal-footer>
                <button class="btn btn-danger" @click="cancel()">
                    <i class="far fa-times-circle mr-2"></i>
                    {{ $t("cancel") }}
                </button>
                <button class="btn btn-primary" @click="save()">
                    <i class="far fa-save mr-2"></i>
                    {{ $t("save") }}
                </button>
            </template>
        </b-modal>
    </div>
</template>
<script>
import { mapState, mapActions } from "vuex";
export default {
    name: "selectEmployee",
    props: ["singleSelected", "sortByDepartment", "data", "value", "disabled"],
    data() {
        return {
            rows: 0,
            perPage: 8,
            currentPage: 1,
            keyword: null,
            listSelectedEmployee: [],
            temporaryListSelectedEmployee: [],
            fields: [
                { key: "department.name", label: "department", sortable: true },
                { key: "nik", label: "nik", sortable: true },
                { key: "name", label: "name", sortable: true },
                {
                    key: "actions",
                    label: "action",
                    tdClass: "text-center"
                }
            ]
        };
    },
    computed: {
        employees() {
            if (this.keyword != null && new String(this.keyword).length > 0) {
                const resultFilter = this.data.filter(item => {
                    return (
                        item.nik.indexOf(this.keyword) > -1 ||
                        this.keyword
                            .toLowerCase()
                            .split(" ")
                            .every(v => item.name.toLowerCase().includes(v)) ||
                        this.keyword
                            .toLowerCase()
                            .split(" ")
                            .every(v =>
                                item.department.name.toLowerCase().includes(v)
                            )
                    );
                });
                this.rows = resultFilter.length;
                return resultFilter;
            } else if (new String(this.keyword).length == 0) {
                this.rows = this.data.length;
                return this.data;
            } else {
                return this.data;
            }
        },
        names() {
            return this.listSelectedEmployee.map(
                item => ` ${item.nik} - ${item.name} (${item.position})`
            );
        }
    },
    watch: {
        data() {
            this.rows = this.data.length;
            if (this.value && this.rows > 0) {
                const value = this.value.toString().split(",");
                let result = [];
                value.forEach(element => {
                    const list = this.data.filter(item => item.id == element);
                    list.forEach(list => {
                        result.push({
                            id: list.id,
                            nik: list.nik,
                            name: list.name,
                            position: list.position.name
                        });
                    });
                });
                this.listSelectedEmployee = result;
            }
        }
    },
    methods: {
        selectedEmployee(id, nik, name, position) {
            if (this.singleSelected) this.listSelectedEmployee = [];

            const index = this.listSelectedEmployee.findIndex(
                item => item.id == id
            );

            if (index > -1) {
                this.listSelectedEmployee.splice(index, 1);
            } else {
                this.listSelectedEmployee.push({
                    id: id,
                    nik: nik,
                    name: name,
                    position: position
                });
            }
        },
        checkSelected(id) {
            return this.listSelectedEmployee.find(item => item.id == id);
        },
        deselect(index) {
            this.temporaryListSelectedEmployee = [...this.listSelectedEmployee];
            this.listSelectedEmployee.splice(index, 1);
        },
        openModal() {
            this.temporaryListSelectedEmployee = [...this.listSelectedEmployee];
            this.$refs["my-modal"].show();
        },
        hideModal() {
            this.keyword = null;
            this.$refs["my-modal"].hide();
        },
        save() {
            const ids = this.listSelectedEmployee
                .map(item => item.id)
                .toString();
            this.$emit("selectedEmployee", ids);
            this.hideModal();
        },
        cancel() {
            this.listSelectedEmployee = [...this.temporaryListSelectedEmployee];
            this.hideModal();
        }
    }
};
</script>
<style scoped>
.form-control-sm-plus-height {
    height: calc(1.5em + 0.5rem + 6.3px) !important;
}

.list-selected-employee {
    display: flex;
    flex-wrap: wrap;
    margin-bottom: 5px;
}

.list-selected-employee .employee {
    display: flex;
    align-items: center;
    background-color: #f0f0f0;
    border: 1px solid rgba(60, 60, 60, 0.26);
    border-radius: 4px;
    color: #333;
    line-height: 1.4;
    margin: 4px 2px 0px 2px;
    padding: 0 0.25em;
}
.list-selected-employee .employee .deselect {
    display: inline-flex;
    -webkit-appearance: none;
    -moz-appearance: none;
    appearance: none;
    margin-left: 4px;
    padding: 0;
    border: 0;
    cursor: pointer;
}

.list-selected-employee .employee .deselect i {
    color: rgb(63, 63, 63);
}

input[type="text"]:disabled {
    cursor: not-allowed;
}
</style>
