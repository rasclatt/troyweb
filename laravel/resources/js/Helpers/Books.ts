export const isOverDue = (date: string): boolean => {
    const dueDate = new Date(date);
    const today = new Date();
    if(dueDate < today) {
        return true;
    }
    return false;
}