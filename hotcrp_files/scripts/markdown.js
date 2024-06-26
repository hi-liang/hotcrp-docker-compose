(() => {
    marked.use(markedKatex({
        throwOnError: false,
        output: 'htmlAndMathml',
    }));
    const purifyOpts = {
        USE_PROFILES: {html: true},
        FORBID_TAGS: ['style', 'img'],
        ADD_TAGS: ['svg', 'path'],
        ADD_ATTR: ['height', 'width', 'viewBox', 'd'],
        ALLOW_DATA_ATTR: false,
        SANITIZE_NAMED_PROPS: true,
    };
    globalThis.render_text.add_format({
        format: 1,
        has_preview: true,
        can_preview: true,
        render: function(text) {
            if (!text) return '';
            const markdownText = DOMPurify.sanitize(marked.parse(text),
                purifyOpts);
            return `<div class="markdown">${markdownText}</div>`;
        },
        render_inline: function(text) {
            if (!text) return '';
            return DOMPurify.sanitize(marked.parseInline(text), purifyOpts);
        },
        description: 'Markdown styling',
    });
})();
