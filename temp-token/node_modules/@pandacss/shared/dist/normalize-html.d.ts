declare function normalizeHTMLProps(props: Record<string, any>): {
    [k: string]: any;
};
declare namespace normalizeHTMLProps {
    var keys: string[];
}

export { normalizeHTMLProps };
