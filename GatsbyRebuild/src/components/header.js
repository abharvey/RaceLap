import { Link } from "gatsby"
import { arrayOf, oneOfType, node, string } from "prop-types"
import React from "react"

import styled from "styled-components"

// TODO: integrate theme-responsively library
const HeaderBG = styled.div`
  position: fixed;
  z-index: 99999;
  top: 0px;
  left: 0px;
  right: 0px;
  height: 90px;
  background-color: #353535;
`

const Header = ({ children }) => (
  <header
    style={{
      background: `rebeccapurple`,
      marginBottom: `1.45rem`,
    }}
  >
    <HeaderBG>
      <h1 style={{ margin: 0 }}>
        <Link
          to="/"
          style={{
            color: `white`,
            textDecoration: `none`,
          }}
        >
          {children}
        </Link>
      </h1>
    </HeaderBG>
  </header>
)

Header.propTypes = {
  children: arrayOf(oneOfType([node, string])),
}

Header.defaultProps = {
  siteTitle: ``,
}

export default Header
