import React from "react"
import styled from "styled-components"

import Cell from "./Cell"

import { firstPlace, secondPlace, thirdPlace } from "./trophyIcons"

const Place = styled.span`
  min-width: 24px;
  max-width: 24px;

  & tr:first-child,
  & tr:nth-of-type(2),
  & tr:nth-of-type(3) {
    text-align: left;
    text-indent: -999em;
  }

  & tr:first-child {
    ${firstPlace}
  }

  & tr:nth-of-type(2) {
    ${secondPlace}
  }

  & tr:nth-of-type(3) {
    ${thirdPlace}
  }
`

export default ({ children, ...rest }) => (
  <Cell {...rest}>
    <Place>{children}</Place>
  </Cell>
)
