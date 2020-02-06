import React from "react"
import styled from "styled-components"

import {
  filterForQuickResults,
  filterOutSkipColumns,
  sortColumnsByPriority,
} from "./utils"

import Cell from "./Cell"
import PlaceCell from "./PlaceCell"

const ParticipantRow = styled.tr``

const cellComponent = (col, string) => {
  if (col === "Place") {
    return <PlaceCell>{string}</PlaceCell>
  }
  return <Cell column={col}>{string}</Cell>
}

export default ({ columnHeaders, isDetailed, rider }) => (
  <ParticipantRow>
    {columnHeaders
      .filter(filterForQuickResults(isDetailed))
      .filter(filterOutSkipColumns)
      .sort(sortColumnsByPriority)
      .map(col => cellComponent(col, rider[col]))}
  </ParticipantRow>
)
