import Id from "../value-object/id.value-object";

export default class BaseEntity {

    private _id: Id;
    private created_at: Date;
    private updated_at: Date;

    constructor(id?: Id) {
        this._id = id;
        this.created_at = new Date();
        this.updated_at = new Date();
    }


    get id(): Id {
        return this._id;
    }

    get createdAt(): Date {
        return this.created_at;
    }

    get updatedAt(): Date {
        return this.updated_at;
    }

    set updatedAt(date: Date) {
        this.updated_at = date;
    }

}