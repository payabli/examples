export function xCopyPaste(Alpine) {
  Alpine.directive("copypaste", (el, { modifiers, expression }) => {
    const isDark = modifiers.includes("dark")
    const button = createButton(isDark)

    let isPopupVisible = false
    let hideTimeout

    const defaultOffset = "0.75rem"
    const positions = {
      top: { top: "0", left: "50%", transform: "translate(-50%, -100%)" },
      topleft: { top: "0", left: "0", transform: "translate(-100%, -100%)" },
      topright: { top: "0", right: "0", transform: "translate(100%, -100%)" },
      bottom: { bottom: "0", left: "50%", transform: "translate(-50%, 100%)" },
      bottomleft: { bottom: "0", left: "0", transform: "translate(-100%, 100%)" },
      bottomright: { bottom: "0", right: "0", transform: "translate(100%, 100%)" },
      left: { top: "50%", left: "0", transform: "translate(-100%, -50%)" },
      right: { top: "50%", right: "0", transform: "translate(100%, -50%)" },
    }

    const position = Object.keys(positions).find((pos) => modifiers.includes(pos)) || "topright"
    const basePosition = { ...positions[position] }

    // Parse offset value
    const offsetIndex = modifiers.indexOf("offset")
    let offsetValue = defaultOffset
    if (offsetIndex !== -1 && offsetIndex < modifiers.length - 1) {
      offsetValue = modifiers.slice(offsetIndex + 1).join(".")
    }

    if (position.includes("top")) {
      basePosition.top = `calc(0px - ${offsetValue})`
    } else if (position.includes("bottom")) {
      basePosition.bottom = `calc(0px - ${offsetValue})`
    }

    if (position === "left" || position.includes("left")) {
      basePosition.left = `calc(0px - ${offsetValue})`
    } else if (position === "right" || position.includes("right")) {
      basePosition.right = `calc(0px - ${offsetValue})`
    }

    // Adjust transform for center positions
    if (position === "top" || position === "bottom") {
      basePosition.transform = `translateX(-50%)`
    } else if (position === "left" || position === "right") {
      basePosition.transform = `translateY(-50%)`
    } else {
      basePosition.transform = "none"
    }

    Object.assign(button.style, basePosition)

    const showButton = () => {
      clearTimeout(hideTimeout)
      button.style.opacity = "1"
      button.style.pointerEvents = "auto"
      isPopupVisible = true
    }

    const hideButton = () => {
      hideTimeout = setTimeout(() => {
        button.style.opacity = "0"
        button.style.pointerEvents = "none"
        isPopupVisible = false
      }, 100)
    }

    const isActive = modifiers.includes("active")

    el.style.position = "relative"
    el.appendChild(button)

    if (modifiers.includes("text") || modifiers.includes("code")) {
      const popup = createPopup(modifiers, expression, isDark)
      const popupButton = button.cloneNode(true)
      Object.assign(popupButton.style, basePosition)
      popup.appendChild(popupButton)
      document.body.appendChild(popup)

      const showPopup = () => {
        clearTimeout(hideTimeout)
        popup.style.opacity = "1"
        popup.style.pointerEvents = "auto"
        popupButton.style.opacity = "1"
        popupButton.style.pointerEvents = "auto"
        isPopupVisible = true
        adjustPopupPosition(popup, el, position)
      }

      const hidePopup = () => {
        if (!isActive) {
          hideTimeout = setTimeout(() => {
            popup.style.opacity = "0"
            popup.style.pointerEvents = "none"
            popupButton.style.opacity = "0"
            popupButton.style.pointerEvents = "none"
            isPopupVisible = false
          }, 100)
        }
      }

      if (isActive) {
        showPopup()
      } else if (modifiers.includes("click")) {
        el.addEventListener("click", (e) => {
          e.stopPropagation()
          if (!isPopupVisible) {
            showPopup()
          }
        })

        document.addEventListener("click", (e) => {
          if (isPopupVisible && !popup.contains(e.target) && !el.contains(e.target)) {
            hidePopup()
          }
        })
      } else {
        el.addEventListener("mouseenter", showPopup)
        el.addEventListener("mouseleave", hidePopup)
        popup.addEventListener("mouseenter", showPopup)
        popup.addEventListener("mouseleave", hidePopup)
      }

      setupCopyButton(popupButton, expression, isDark)
    } else {
      if (isActive) {
        showButton()
      } else if (modifiers.includes("click")) {
        el.addEventListener("click", (e) => {
          e.stopPropagation()
          if (!isPopupVisible) {
            showButton()
          }
        })

        document.addEventListener("click", (e) => {
          if (isPopupVisible && !button.contains(e.target) && !el.contains(e.target)) {
            hideButton()
          }
        })
      } else {
        el.addEventListener("mouseenter", showButton)
        el.addEventListener("mouseleave", hideButton)
        button.addEventListener("mouseenter", showButton)
        button.addEventListener("mouseleave", hideButton)
      }

      setupCopyButton(button, expression, isDark)
    }
  })

  function createButton(isDark) {
    const button = document.createElement("button")
    button.innerHTML = `
          <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="${isDark ? "white" : "black"}" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-5 h-5">
              <rect width="14" height="14" x="8" y="8" rx="2" ry="2" />
              <path d="M4 16c-1.1 0-2-.9-2-2V4c0-1.1.9-2 2-2h10c1.1 0 2 .9 2 2" />
          </svg>
      `
    button.setAttribute("aria-label", "Copy to clipboard")
    button.style.position = "absolute"
    button.style.padding = "0.25rem"
    button.style.backgroundColor = isDark ? "#374151" : "white"
    button.style.border = `1px solid ${isDark ? "#4B5563" : "#e2e8f0"}`
    button.style.borderRadius = "0.375rem"
    button.style.boxShadow = "0 1px 2px 0 rgba(0, 0, 0, 0.05)"
    button.style.transition = "all 0.2s, box-shadow 0.5s" // Separate transition for box-shadow
    button.style.opacity = "0"
    button.style.pointerEvents = "none"
    button.style.zIndex = "20"
    button.style.outline = "none"
    button.style.width = "fit-content"
    button.style.height = "fit-content"
    button.classList.add("x-copypaste-button")
    return button
  }

  function createPopup(modifiers, expression, isDark) {
    const popup = document.createElement("div")
    popup.style.position = "fixed"
    popup.style.marginBottom = "0.5rem"
    popup.style.padding = "0.85rem"
    popup.style.backgroundColor = isDark ? "#1F2937" : "white"
    popup.style.border = `1px solid ${isDark ? "#374151" : "#e2e8f0"}`
    popup.style.borderRadius = "0.375rem"
    popup.style.boxShadow = "0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06)"
    popup.style.transition = "all 0.2s"
    popup.style.opacity = "0"
    popup.style.pointerEvents = "none"
    popup.style.zIndex = "10"
    popup.classList.add("x-copypaste-popup")

    if (modifiers.includes("code")) {
      const codeBlock = document.createElement("pre")
      codeBlock.style.backgroundColor = isDark ? "#111827" : "#1a202c"
      codeBlock.style.color = "white"
      codeBlock.style.padding = "0.75rem"
      codeBlock.style.borderRadius = "0.25rem"
      codeBlock.style.fontSize = "0.875rem"
      codeBlock.style.overflowX = "auto"
      const code = document.createElement("code")
      code.textContent = expression
      codeBlock.appendChild(code)
      popup.appendChild(codeBlock)
    } else {
      popup.textContent = expression
      popup.style.color = isDark ? "#D1D5DB" : "inherit"
    }

    return popup
  }

  function setupCopyButton(button, textToCopy, isDark) {
    button.addEventListener("click", (e) => {
      e.stopPropagation()
      navigator.clipboard
        .writeText(textToCopy)
        .then(() => {
          button.style.boxShadow = `0 0 0 3px rgba(59, 130, 246, 0.5)` // Light blue ring with transparency
          button.innerHTML = `
                  <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#10B981" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-5 h-5">
                      <polyline points="20 6 9 17 4 12" />
                  </svg>
              `
          button.setAttribute("aria-label", "Copied!")
          setTimeout(() => {
            button.style.boxShadow = "0 1px 2px 0 rgba(0, 0, 0, 0.05)"
            button.innerHTML = `
                      <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="${isDark ? "white" : "black"}" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-5 h-5">
                          <rect width="14" height="14" x="8" y="8" rx="2" ry="2" />
                          <path d="M4 16c-1.1 0-2-.9-2-2V4c0-1.1.9-2 2-2h10c1.1 0 2 .9 2 2" />
                      </svg>
                  `
            button.setAttribute("aria-label", "Copy to clipboard")
          }, 2000)
        })
        .catch((err) => console.error("Failed to copy: ", err))
    })
  }

  function adjustPopupPosition(popup, baseElement, position) {
    const rect = baseElement.getBoundingClientRect()
    const popupRect = popup.getBoundingClientRect()

    let top, left

    // Calculate position based on the specified position
    if (position.includes("top")) {
      top = rect.top - popupRect.height
    } else if (position.includes("bottom")) {
      top = rect.bottom
    } else {
      top = rect.top + (rect.height - popupRect.height) / 2
    }

    if (position.includes("left")) {
      left = rect.left
    } else if (position.includes("right")) {
      left = rect.right - popupRect.width
    } else {
      left = rect.left + (rect.width - popupRect.width) / 2
    }

    // Apply the calculated position
    popup.style.position = "fixed"
    popup.style.top = `${top}px`
    popup.style.left = `${left}px`
    popup.style.transform = "none"
  }
}

export function test() {
  alert("Hello, world!")
}
