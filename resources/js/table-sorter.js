/**
 * Table Sorter Utility
 * Simple and reusable table sorting functionality
 */
class TableSorter {
    constructor(tableSelector, options = {}) {
        this.table = document.querySelector(tableSelector);
        if (!this.table) return;

        this.tbody = this.table.querySelector("tbody");
        this.currentSort = options.defaultSort || null;
        this.currentDirection = options.defaultDirection || "asc";
        this.onSort = options.onSort || null;

        this.init();
    }

    init() {
        const headers = this.table.querySelectorAll("thead th[data-sort]");

        headers.forEach((header) => {
            header.addEventListener("click", () => this.handleSort(header));
            header.addEventListener("keypress", (e) => {
                if (e.key === "Enter" || e.key === " ") {
                    e.preventDefault();
                    this.handleSort(header);
                }
            });
        });
    }

    handleSort(header) {
        const column = header.dataset.sort;
        const direction = header.dataset.direction;

        // Update sort state
        this.currentSort = column;
        this.currentDirection = direction;

        // Update all headers
        this.updateHeaders();

        // Perform sort
        this.sortTable(column, direction);

        // Call callback if provided
        if (this.onSort) {
            this.onSort(column, direction);
        }
    }

    updateHeaders() {
        const headers = this.table.querySelectorAll("thead th[data-sort]");

        headers.forEach((header) => {
            const column = header.dataset.sort;
            const isActive = column === this.currentSort;

            if (isActive) {
                // Toggle direction for next click
                header.dataset.direction =
                    this.currentDirection === "asc" ? "desc" : "asc";
                header.setAttribute(
                    "aria-sort",
                    this.currentDirection === "asc" ? "ascending" : "descending"
                );

                // Update icon
                const icon = header.querySelector("svg");
                if (icon) {
                    icon.classList.remove("text-neutral-400", "opacity-0");
                    icon.classList.add("text-red-600");

                    if (this.currentDirection === "asc") {
                        icon.innerHTML =
                            '<path fill-rule="evenodd" d="M14.707 12.707a1 1 0 01-1.414 0L10 9.414l-3.293 3.293a1 1 0 01-1.414-1.414l4-4a1 1 0 011.414 0l4 4a1 1 0 010 1.414z" clip-rule="evenodd"/>';
                    } else {
                        icon.innerHTML =
                            '<path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd"/>';
                    }
                }
            } else {
                // Reset inactive headers
                header.dataset.direction = "asc";
                header.setAttribute("aria-sort", "none");

                const icon = header.querySelector("svg");
                if (icon) {
                    icon.classList.add(
                        "text-neutral-400",
                        "opacity-0",
                        "group-hover:opacity-100"
                    );
                    icon.classList.remove("text-red-600");
                    icon.innerHTML =
                        '<path d="M5 12a1 1 0 102 0V6.414l1.293 1.293a1 1 0 001.414-1.414l-3-3a1 1 0 00-1.414 0l-3 3a1 1 0 001.414 1.414L5 6.414V12zM15 8a1 1 0 10-2 0v5.586l-1.293-1.293a1 1 0 00-1.414 1.414l3 3a1 1 0 001.414 0l3-3a1 1 0 00-1.414-1.414L15 13.586V8z"/>';
                }
            }
        });
    }

    sortTable(column, direction) {
        const rows = Array.from(this.tbody.querySelectorAll("tr"));

        rows.sort((a, b) => {
            const aCell = a.querySelector(`td[data-${column}]`);
            const bCell = b.querySelector(`td[data-${column}]`);

            if (!aCell || !bCell) return 0;

            let aVal = aCell.dataset[column] || aCell.textContent.trim();
            let bVal = bCell.dataset[column] || bCell.textContent.trim();

            // Try to parse as number
            const aNum = parseFloat(aVal);
            const bNum = parseFloat(bVal);

            if (!isNaN(aNum) && !isNaN(bNum)) {
                aVal = aNum;
                bVal = bNum;
            }

            // Compare
            if (aVal < bVal) return direction === "asc" ? -1 : 1;
            if (aVal > bVal) return direction === "asc" ? 1 : -1;
            return 0;
        });

        // Reorder DOM
        rows.forEach((row) => this.tbody.appendChild(row));
    }

    // Method to sort programmatically
    sort(column, direction = "asc") {
        this.currentSort = column;
        this.currentDirection = direction;
        this.updateHeaders();
        this.sortTable(column, direction);
    }
}

// Make it available globally
window.TableSorter = TableSorter;
